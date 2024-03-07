<?php

/**
 * Class lisner_statistics
 *
 * @author pebas
 * @ver 1.0.0
 */

class lisner_statistics {

	protected static $_instance = null;

	/**
	 * Default paid listings table name
	 *
	 * @var $paid_listings_table
	 */
	public static $stats_table;

	public static $stat_types = array( 'view', 'focus', 'ctr', 'lead', 'review', 'favourite' );

	/**
	 * @return null|lisner_statistics
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action( 'init', array( $this, 'create_tables' ), 10 );

		add_action( 'lisner_ajax_listing_ctr_call', array( $this, 'add_listing_ctr_ajax' ) );
		add_action( 'lisner_ajax_nopriv_listing_ctr_call', array( $this, 'add_listing_ctr_ajax' ) );
		add_action( 'lisner_ajax_listing_lead_call', array( $this, 'add_listing_lead_ajax' ) );
		add_action( 'lisner_ajax_nopriv_listing_lead_call', array( $this, 'add_listing_lead_ajax' ) );
		add_action( 'lisner_ajax_listing_show_chart', array( $this, 'show_chart_ajax' ) );
		add_action( 'comment_post', array( $this, 'add_listing_review' ), 10, 2 );
	}

	public static function is_stat_enabled( $setting = '' ) {
		$option        = get_option( 'pbs_option' );
		$stats_enabled = isset( $option['listing-statistics-enable'] ) && $option['listing-statistics-enable'] ? true : false;

		if ( $stats_enabled ) {
			if ( empty( $setting ) ) {
				return true;
			} else {
				return isset( $option[ $setting ] ) && $option[ $setting ] ? true : false;
			}
		}

		return false;
	}

	/**
	 * Get all listing ids associated with a given author
	 * --------------------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return array
	 */
	public static function get_author_listing_ids( $user_id = '' ) {
		$user_id  = empty( $user_id ) ? get_current_user_id() : $user_id;
		$ids      = array();
		$listings = get_posts( array(
			'post_type'      => 'job_listing',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'author'         => $user_id
		) );
		foreach ( $listings as $listing ) {
			$ids[] = $listing->ID;
		}

		return $ids;

	}

	/**
	 * Get all views from all author listings
	 * --------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return int
	 */
	public static function get_author_visits( $user_id = '' ) {
		$user_id = empty( $user_id ) ? get_current_user_id() : $user_id;

		$count    = 0;
		$listings = get_posts( array(
			'post_type'      => 'job_listing',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'author'         => $user_id
		) );
		foreach ( $listings as $listing ) {
			$count = $count + lisner_listings::get_listing_views_count( $listing->ID );
		}

		return $count;
	}

	/**
	 * Get count of how many times listings
	 * by a given author were in page focus
	 * ---------------------------------------
	 *
	 * @param $type
	 * @param string $user_id
	 *
	 * @return int
	 */
	public static function get_author_stat( $type, $user_id = '' ) {
		$user_id   = empty( $user_id ) ? get_current_user_id() : $user_id;
		$count     = 0;
		$ids       = self::get_author_listing_ids( $user_id );
		$ids       = implode( ',', $ids );
		$table     = self::$stats_table;
		$condition = "listing_id IN({$ids}) AND type='{$type}'";
		$results   = self::get_data_from_db( $table, 'count', $condition );

		foreach ( $results as $result ) {
			$count = $count + $result->count;
		}

		return $count;
	}

	/**
	 * Get the stats for the given listing
	 * -----------------------------------
	 *
	 * @param $type
	 * @param string $listing_id
	 *
	 * @return int
	 */
	public static function get_listing_stat( $type, $listing_id = '' ) {
		$listing_id = ! empty( $listing_id ) ? $listing_id : get_the_ID();
		$count      = 0;
		$table      = self::$stats_table;
		$condition  = "listing_id={$listing_id} AND type='{$type}'";
		$results    = self::get_data_from_db( $table, 'count', $condition );

		foreach ( $results as $result ) {
			$count = $count + $result->count;
		}

		return $count;
	}

	/**
	 * Calculate all time CTR rate for the given listing
	 * -------------------------------------------------
	 *
	 * @param string $listing_id
	 *
	 * @return string
	 */
	public static function calculate_listing_ctr( $listing_id = '' ) {
		$listing_id = empty( $listing_id ) ? get_current_user_id() : $listing_id;
		$focus      = self::get_listing_stat( 'focus', $listing_id );
		$ctr        = self::get_listing_stat( 'ctr', $listing_id );

		if ( 0 != $ctr ) {
			$rate = number_format_i18n( ( $ctr / $focus ) * 100, '2' );
		} else {
			$rate = 0;
		}

		return esc_html( $rate . '%' );
	}

	/**
	 * Calculate all time CTR rate for the given author
	 * ------------------------------------------------
	 *
	 * @param string $user_id
	 *
	 * @return string
	 */
	public static function calculate_author_ctr( $user_id = '' ) {
		$user_id = empty( $user_id ) ? get_current_user_id() : $user_id;
		$focus   = self::get_author_stat( 'focus', $user_id );
		$ctr     = self::get_author_stat( 'ctr', $user_id );

		if ( 0 != $ctr ) {
			$rate = number_format_i18n( ( $ctr / $focus ) * 100, '2' );
		} else {
			$rate = 0;
		}

		return esc_html( $rate . '%' );
	}

	/**
	 * Get days of the week in array
	 * -----------------------------
	 *
	 * @param $current_day
	 *
	 * @return array
	 */
	public static function get_weekdays( $current_day ) {
		$start_date = strtotime( $current_day . '-7 day' );
		$start_date = date( 'Y-m-d', $start_date );
		$week       = array(
			strtotime( $start_date . "+1 day" ),
			strtotime( $start_date . "+2 day" ),
			strtotime( $start_date . "+3 day" ),
			strtotime( $start_date . "+4 day" ),
			strtotime( $start_date . "+5 day" ),
			strtotime( $start_date . "+6 day" ),
			strtotime( $start_date . "+7 day" )
		);

		return $week;
	}

	/**
	 * Get the days of the given month
	 * -------------------------------
	 *
	 * @param $month
	 * @param $year
	 *
	 * @return array
	 */
	public static function get_month_days( $month, $year ) {
		$num         = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$dates_month = array();

		for ( $i = 1; $i <= $num; $i ++ ) {
			$mktime            = mktime( 0, 0, 0, $month, $i, $year );
			$date              = date( "Y-m-d", $mktime );
			$date              = strtotime( $date );
			$dates_month[ $i ] = $date;
		}

		return $dates_month;
	}

	/**
	 * Get all months
	 *
	 * @return array
	 */
	public static function get_all_months() {
		$months = array(
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December'
		);

		return $months;
	}

	/**
	 * Display chart for all listings combined
	 * ---------------------------------------
	 *
	 */
	public function show_chart_ajax() {
		$listing_id = isset( $_POST['listing_id'] ) ? $_POST['listing_id'] : '';
		$user_id    = get_current_user_id();
		$duration   = $_POST['duration'];
		$day        = date( "l" );
		$year       = date( "Y" );
		$month      = date( "F" );
		$date       = date( 'Y-m-d' );
		$time       = strtotime( $date );
		$table      = self::$stats_table;
		$count      = 0;

		if ( empty( $listing_id ) ) {
			$condition = "user_id='$user_id'";
		} else {
			$condition = "user_id='$user_id' AND listing_id='$listing_id'";
		}
		$rows = self::get_data_from_db( $table, '*', $condition );

		if ( $duration == "weekly" ) {

			$weekdays = self::get_weekdays( $date );
			if ( ! empty( $weekdays ) ) {
				foreach ( $weekdays as $day ) {
					$focus_count  = 0;
					$ctr_count    = 0;
					$view_count   = 0;
					$lead_count   = 0;
					$review_count = 0;
					if ( ! empty( $rows ) ) {
						foreach ( $rows as $row ) {
							if ( isset( $row->date ) ) {
								$row_date = $row->date;
								if ( $row_date == $day ) {

									// focus count
									if ( 'focus' == $row->type ) {
										$row_count   = $row->count;
										$focus_count = $focus_count + $row_count;
									}

									// ctr count
									if ( 'ctr' == $row->type ) {
										$row_count = $row->count;
										$ctr_count = $ctr_count + $row_count;
									}

									// view count
									if ( 'view' == $row->type ) {
										$row_count  = $row->count;
										$view_count = $view_count + $row_count;
									}

									// lead count
									if ( 'lead' == $row->type ) {
										$row_count  = $row->count;
										$lead_count = $lead_count + $row_count;
									}

									// review count
									if ( 'review' == $row->type ) {
										$row_count    = $row->count;
										$review_count = $review_count + $row_count;
									}
								}
							}
						}
					}

					$response[] = array(
						'day'    => date( "l", $day ),
						'focus'  => $focus_count,
						'ctr'    => $ctr_count,
						'view'   => $view_count,
						'lead'   => $lead_count,
						'review' => $review_count,
					);
				}
			}

		}

		if ( $duration == 'monthly' ) {
			$month = date( 'm' );
			$year  = date( 'Y' );

			if ( ! empty( $listing_id ) ) {
				$condition = "user_id='$user_id' AND listing_id='$listing_id' AND MONTH(FROM_UNIXTIME(date))='$month'";
			} else {
				$condition = "user_id='$user_id' AND MONTH(FROM_UNIXTIME(date))='$month'";
			}
			$rows       = self::get_data_from_db( $table, '*', $condition );
			$month_days = self::get_month_days( $month, $year );
			if ( ! empty( $month_days ) ) {
				$day_count = 1;
				foreach ( $month_days as $day ) {
					$focus_count  = 0;
					$ctr_count    = 0;
					$view_count   = 0;
					$lead_count   = 0;
					$review_count = 0;
					if ( ! empty( $rows ) ) {
						foreach ( $rows as $row ) {
							if ( isset( $row->date ) ) {
								$row_date = $row->date;
								if ( $row_date == $day ) {

									// focus count
									if ( 'focus' == $row->type ) {
										$row_count   = $row->count;
										$focus_count = $focus_count + $row_count;
									}

									// ctr count
									if ( 'ctr' == $row->type ) {
										$row_count = $row->count;
										$ctr_count = $ctr_count + $row_count;
									}

									// view count
									if ( 'view' == $row->type ) {
										$row_count  = $row->count;
										$view_count = $view_count + $row_count;
									}

									// lead count
									if ( 'lead' == $row->type ) {
										$row_count  = $row->count;
										$lead_count = $lead_count + $row_count;
									}

									// review count
									if ( 'review' == $row->type ) {
										$row_count    = $row->count;
										$review_count = $review_count + $row_count;
									}
								}
							}
						}
					}

					$response[] = array(
						'day'    => $day_count,
						'focus'  => $focus_count,
						'ctr'    => $ctr_count,
						'view'   => $view_count,
						'lead'   => $lead_count,
						'review' => $review_count,
					);

					$day_count ++;
				}
			}

		}

		if ( $duration == 'yearly' ) {
			$year = date( 'Y' );

			if ( ! empty( $listing_id ) ) {
				$condition = "user_id='$user_id' AND listing_id='$listing_id' AND YEAR(FROM_UNIXTIME(date))='$year'";
			} else {
				$condition = "user_id='$user_id' AND YEAR(FROM_UNIXTIME(date))='$year'";
			}
			$rows   = self::get_data_from_db( $table, '*', $condition );
			$months = self::get_all_months();
			if ( ! empty( $months ) ) {
				foreach ( $months as $month ) {
					$focus_count  = 0;
					$ctr_count    = 0;
					$view_count   = 0;
					$lead_count   = 0;
					$review_count = 0;
					if ( ! empty( $rows ) ) {
						foreach ( $rows as $row ) {
							if ( isset( $row->date ) ) {
								$row_date   = $row->date;
								$this_month = date( 'F', $row_date );
								if ( $month == $this_month ) {

									// focus count
									if ( 'focus' == $row->type ) {
										$row_count   = $row->count;
										$focus_count = $focus_count + $row_count;
									}

									// ctr count
									if ( 'ctr' == $row->type ) {
										$row_count = $row->count;
										$ctr_count = $ctr_count + $row_count;
									}

									// view count
									if ( 'view' == $row->type ) {
										$row_count  = $row->count;
										$view_count = $view_count + $row_count;
									}

									// lead count
									if ( 'lead' == $row->type ) {
										$row_count  = $row->count;
										$lead_count = $lead_count + $row_count;
									}

									// review count
									if ( 'review' == $row->type ) {
										$row_count    = $row->count;
										$review_count = $review_count + $row_count;
									}
								}
							}
						}
					}

					$response[] = array(
						'day'    => $month,
						'focus'  => $focus_count,
						'ctr'    => $ctr_count,
						'view'   => $view_count,
						'lead'   => $lead_count,
						'review' => $review_count,
					);

				}
			}

		}

		if ( $duration == 'weekly' ) {
			$message = esc_html__( 'Last 7 Days', 'lisner-core' );
		} elseif ( $duration == 'monthly' ) {
			$message = esc_html__( 'Last Month', 'lisner-core' );
		} else {
			$message = esc_html__( 'Last Year', 'lisner-core' );
		}
		$result['result'] = $message;
		$result['count']  = $count;
		$result['data']   = $response;

		wp_send_json_success( $result );
	}

	/**
	 * Add listing view to the stats table
	 * -----------------------------------
	 *
	 * @param $user_id
	 * @param $listing_id
	 */
	public static function add_listing_view( $user_id, $listing_id ) {
		self::update_stats_table( $user_id, $listing_id, 'view' );
	}

	/**
	 * Add visible listing to the stats table
	 * -----------------------------------
	 *
	 * @param $user_id
	 * @param $listing_id
	 */
	public static function add_listing_focus( $user_id, $listing_id ) {
		self::update_stats_table( $user_id, $listing_id, 'focus' );
	}

	/**
	 * Add listing ctr to the stats table
	 * -----------------------------------
	 *
	 * @param $user_id
	 * @param $listing_id
	 */
	public static function add_listing_ctr( $user_id, $listing_id ) {
		self::update_stats_table( $user_id, $listing_id, 'ctr' );
	}

	/**
	 * Add listing review to the stats table
	 * -----------------------------------
	 *
	 * @param $comment_id
	 * @param $comment_approved
	 */
	public static function add_listing_review( $comment_id, $comment_approved ) {
		$comment    = get_comment( $comment_id );
		$listing_id = $comment->comment_post_ID;
		$listing    = get_post( $listing_id );
		$user_id    = get_post_meta( $listing_id, '_job_author', true );
		$user_id    = isset( $user_id ) && ! empty( $user_id ) ? $user_id : 1;
		if ( 'job_listing' == $listing->post_type ) {
			wp_update_comment( array(
				'comment_ID'   => $comment_id,
				'comment_type' => 'review'
			) );
			self::update_stats_table( $user_id, $listing_id, 'review' );
		}
	}

	/**
	 * Add listing ctr ajax call
	 * -----------------------------------
	 *
	 * @return false|mixed|string|void
	 */
	public static function add_listing_ctr_ajax() {
		$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
		$listing_id = isset( $_REQUEST['listing_id'] ) ? $_REQUEST['listing_id'] : '';

		if ( ! empty( $user_id ) && ! empty( $listing_id ) && wp_verify_nonce( $_REQUEST['ctr_nonce'], 'ctr-nonce' ) ) {
			self::add_listing_ctr( $user_id, $listing_id );
			$result['ctr_success'] = esc_html__( 'CTR success.', 'lisner-core' );
		} else {
			$result['ctr_error'] = esc_html__( 'CTR error.', 'lisner-core' );
		}

		wp_send_json_success( wp_json_encode( $result ) );
	}

	/**
	 * Add listing lead to the stats table
	 * -----------------------------------
	 *
	 * @param $user_id
	 * @param $listing_id
	 */
	public static function add_listing_lead( $user_id, $listing_id ) {
		self::update_stats_table( $user_id, $listing_id, 'lead' );
	}

	/**
	 * Add listing lead ajax call
	 * -----------------------------------
	 *
	 * @return false|mixed|string|void
	 */
	public static function add_listing_lead_ajax() {
		$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
		$listing_id = isset( $_REQUEST['listing_id'] ) ? $_REQUEST['listing_id'] : '';

		if ( ! empty( $user_id ) && ! empty( $listing_id ) && wp_verify_nonce( $_REQUEST['lead_nonce'], 'contact_listing' ) ) {
			self::add_listing_lead( $user_id, $listing_id );
			$result['ctr_success'] = esc_html__( 'Lead success.', 'lisner-core' );
		} else {
			$result['ctr_error'] = esc_html__( 'Lead error.', 'lisner-core' );
		}

		wp_send_json_success( wp_json_encode( $result ) );
	}


	/**
	 * Get values from the given table
	 * -------------------------------
	 *
	 * @param $table
	 * @param $data
	 * @param $condition
	 *
	 * @return array|null|object
	 */
	public static function get_data_from_db( $table, $data, $condition ) {
		global $wpdb;

		$prefix = $wpdb->prefix;

		$table = $prefix . $table;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) == $table ) {
			$query  = "";
			$query  = "SELECT $data from $table WHERE $condition ORDER BY id DESC";
			$result = $wpdb->get_results( $query );

			return $result;
		}

		return null;

	}

	/**
	 * Insert data into the table
	 * --------------------------
	 *
	 * @param $table
	 * @param $data
	 *
	 * @return bool
	 */
	public static function insert_data_in_db( $table, $data ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table  = $prefix . $table;
		$result = $wpdb->insert( $table, $data, $format = null );

		if ( ! empty( $result ) && $result > 0 ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Update data in table
	 * ---------------------
	 *
	 * @param $table
	 * @param $data
	 * @param $where
	 *
	 * @return bool
	 */
	public static function update_data_in_db( $table, $data, $where ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table  = $prefix . $table;

		$result = $wpdb->update( $table, $data, $where, $format = null, $where_format = null );
		if ( ! empty( $result ) && $result > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Delete data from custom table
	 * -----------------------------
	 *
	 * @param $table
	 * @param $where
	 *
	 * @return bool
	 */
	public static function delete_data_in_db( $table, $where ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table  = $prefix . $table;
		$result = $wpdb->delete( $table, $where, $where_format = null );

		if ( ! empty( $result ) && $result > 0 ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Update stats table with one of the possible stat types
	 * ------------------------------------------------------
	 *
	 * @param $user_id
	 * @param $listing_id
	 * @param $type
	 */
	public static function update_stats_table( $user_id, $listing_id, $type ) {
		$today           = date( 'Y-m-d' );
		$today_timestamp = strtotime( $today );

		$data = array(
			'user_id'    => $user_id,
			'listing_id' => $listing_id,
			'type'       => $type,
			'date'       => $today_timestamp,
			'count'      => '1'
		);

		$condition = "user_id='$user_id' AND listing_id='$listing_id' AND type='$type' AND date='$today_timestamp'";
		$get_row   = self::get_data_from_db( self::$stats_table, '*', $condition );

		if ( ! empty( $get_row ) ) {
			// update row
			$count = $get_row[0]->count;
			$count ++;
			$data  = array(
				'count' => $count,
			);
			$where = array(
				'user_id'    => $user_id,
				'listing_id' => $listing_id,
				'type'       => $type,
				'date'       => $today_timestamp,
			);
			self::update_data_in_db( self::$stats_table, $data, $where );
		} else {
			/* insert row */
			self::insert_data_in_db( self::$stats_table, $data );
		}
	}

	/**
	 * Create necessary tables
	 */
	public function create_tables() {
		global $wpdb;

		// assign name to table
		self::$stats_table = 'listing_stats';

		$wpdb->hide_errors();
		$table_name = $wpdb->prefix . self::$stats_table;

		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


		// Listing packages table
		$sql = "
			CREATE TABLE $table_name (
  			  id bigint(20) NOT NULL auto_increment,
			  user_id varchar(255) default NULL,
			  listing_id varchar(255) default NULL,
			  type varchar(255) default NULL,
			  date varchar(255) default NULL,
			  count varchar(255) default NULL,
			  PRIMARY KEY  (`id`)
			) $collate;
		";
		dbDelta( $sql );

		// Update version
		update_option( 'lisner_stats_db_version', LISNER_VERSION );

	}

}

/**
 * Instantiate class
 *
 * @return lisner_statistics|null
 */
function lisner_statistics() {
	return lisner_statistics::instance();
}
