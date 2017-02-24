<?php
/**
 * The Example for control settings page
 */
class SW_SettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $TIC_comment;
	private $TIC_distance;
	private $Event_distance;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_setting_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_setting_page()
    {
        // This page will be under "Settings"
        	add_options_page(
            'SagasWhat Site Settings Page',
            'SagasWhat Site Settings',
            'manage_options',
            'my-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->TIC_comment		= get_option( 'tic-comment-end' );
		$this->TIC_distance		= get_option( 'tic-distance' );
		$this->Event_distance	= get_option( 'event-distance' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>SagasWhat Site Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'sw_option_group' );
                do_settings_sections( 'sw-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        add_settings_section(
            'sw_section_id', // ID
            'TIC & Event post Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'sw-setting-admin' // Page
        );

		/**
	     * Option set for TIC Comment
	     */
		register_setting(
            'sw_option_group',	// Option group
            'tic-comment-end'	// Option name
        );
		add_settings_field(
            'tic-comment-end',
            'TIC Comment in end of post',
            array( $this, 'TICComment_callback' ),
            'sw-setting-admin',
            'sw_section_id'
        );

		/**
	     * Option set for TIC Distance
	     */
		register_setting(
            'sw_option_group',	// Option group
            'tic-distance'	// Option name
        );
		add_settings_field(
            'tic-distance',
            'TIC Distance',
            array( $this, 'TICDistance_callback' ),
            'sw-setting-admin',
            'sw_section_id'
        );

		/**
	     * Option set for Event Distance
	     */
		register_setting(
            'sw_option_group',	// Option group
            'event-distance'	// Option name
        );
		add_settings_field(
            'event-distance',
            'Event Distance',
            array( $this, 'EventDistance_callback' ),
            'sw-setting-admin',
            'sw_section_id'
        );
	}
	/**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter TIC & Event settings below:';
    }
	/**
     * Get the setting option and print its value (TIC Comment)
     */
	public function TICComment_callback()
    {
        printf(
            '<input type="text" id="tic-comment-end" name="tic-comment-end" value="%s" class="regular-text" />',
            isset( $this->TIC_comment ) ? esc_attr( $this->TIC_comment ) : ''
        );
    }
	/**
     * Get the setting option and print its value (TIC Distance)
     */
	public function TICDistance_callback()
    {
        printf(
            '<input type="text" id="tic-distance" name="tic-distance" value="%s" class="regular-text" />',
            isset( $this->TIC_distance ) ? esc_attr( $this->TIC_distance ) : ''
        );
    }
	/**
     * Get the setting option and print its value (Event Distance)
     */
	public function EventDistance_callback()
    {
        printf(
            '<input type="text" id="event-distance" name="event-distance" value="%s" class="regular-text" />',
            isset( $this->Event_distance ) ? esc_attr( $this->Event_distance ) : ''
        );
    }
}

if( is_admin() )
    $sw_settings_page = new SW_SettingsPage();
