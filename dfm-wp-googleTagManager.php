<?php
/**
	 * Plugin Name: DFM Google Tag Manager for Blogs
	 * Description: Google Tag Manager code snippet.
	 * Version: 1.2
	 * Author: David leal UPD. Daniel Schneider
**/

// ### Create the settings page for plugin - Network or single site. ## //
function gtm_menu() {
 	if ( is_network_admin() ) {
 		add_submenu_page(
 		'settings.php', 
 		'Google Tag Manager', 
 		'Google Tag Manager', 
 		'manage_options', 
 		'gtm-settings', 
 		'gtm_display'
 		);
 	} else {
    	add_options_page(
		'Google Tag Manager', 
		'Google Tag Manager', 
		'manage_options', 
		'gtm-settings', 
		'gtm_display'
		);
    }
 
} // end gtm_menu
add_action( 'admin_menu', 'gtm_menu' );
add_action( 'network_admin_menu', 'gtm_menu' );

// Select the action if the blog is network admin or not.
function action_form(){
	if ( is_network_admin() ){
		$action = "../options.php";
	} else {
		$action = "options.php";
	}
	echo $action;
}
/**
 * Renders a simple page to display.
 */
function gtm_display() {
?>
    <!-- Create a header in the default WordPress 'wrap' container -->
    <div class="wrap">
     
        <h2>Google Tag Manager Options</h2>
        <?php if ( is_multisite() && is_network_admin() ){ settings_errors(); } ?>
         
        <form method="post" action="<?php action_form(); ?>">
            <?php settings_fields( 'gtm_display_options' ); ?>
            <?php do_settings_sections( 'gtm_display_options' ); ?>         
            <?php submit_button(); ?>
        </form>
         
    </div><!-- /.wrap -->
<?php
} // end gtm_display
 
function gtm_initialize_options() {
 
    // If the gtm options don't exist, create them.
    if( false == get_option( 'gtm_display_options' ) ) {  
        add_option( 'gtm_display_options' );
    } // end if
 
    // First, we register a section. This is necessary since all future options must belong to a 
    add_settings_section(
        'general_settings_section',         // ID used to identify this section and with which to register options
        'Options',                  // Title to be displayed on the administration page
        'gtm_general_options_callback', // Callback used to render the description of the section
        'gtm_display_options'     // Page on which to add this section of options
    );
     
    // Next, we'll introduce the fields for toggling the visibility of content elements.
    add_settings_field( 
        'google_ua',                      // ID used to identify the field throughout the plugin
        'Google UA',                           // The label to the left of the option interface element
        'gtm_google_ua_callback',   // The name of the function responsible for rendering the option interface
        'gtm_display_options',    // The page on which this option will be displayed
        'general_settings_section'
    );
     
    add_settings_field( 
        'publisher_product',                     
        'Publisher Product',              
        'gtm_publisher_product_callback',  
        'gtm_display_options',                    
        'general_settings_section'
    );

    add_settings_field( 
        'quantcast',                     
        'Quantcast',              
        'gtm_quantcast_callback',  
        'gtm_display_options',                    
        'general_settings_section'
    );

    add_settings_field( 
        'comscore',                     
        'Comscore',              
        'gtm_comscore_callback',  
        'gtm_display_options',                    
        'general_settings_section'
    );
     
    add_settings_field( 
        'data_layer',                      
        'Data Layer',               
        'gtm_data_layer_callback',   
        'gtm_display_options',        
        'general_settings_section',         
        array(                              
            'Activate this if you want the Data Layer and DFM-API features in the blog.'
        )
    );
//Show the option of populate values in the network if you are the admin network
if ( is_multisite() && is_network_admin() ){
    add_settings_field( 
        'network_wide',                      
        'Network Wide',               
        'gtm_network_wide_callback',   
        'gtm_display_options',        
        'general_settings_section',         
        array(                              
            'Activate this setting if you want to populate these values network wide.'
        )
    );
}
    // Finally, we register the fields
    register_setting(
        'gtm_display_options',
        'gtm_display_options',
        'gtm_sanitize_options'
    );
     
} // end gtm_initialize_options
add_action( 'admin_init', 'gtm_initialize_options' );
 
//
function gtm_general_options_callback() {
    echo '<p>Select specific values for Google Tag Manager - by DFM.</p>';
} // end gtm_general_options_callback

/*** 

	Input fields 

***/

// Callback for Google UA Input
function gtm_google_ua_callback( $args ) {
     
    // First, we read the values collection
    $options = get_option( 'gtm_display_options' );
     
    // Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
    $google_ua = '';
    if( isset( $options['google_ua'] ) ) {
        $google_ua = $options['google_ua'];
    } // end if
     
    // Render the output
    echo '<input type="text" id="google_ua" name="gtm_display_options[google_ua]" value="' . $google_ua . '" />';
     
     
} // end gtm_google_ua_callback
 
// Callback for Publisher Product Input
function gtm_publisher_product_callback( $args ) {

    $options = get_option( 'gtm_display_options' );
     
    // we need to make sure the element is defined in the options. If not, we'll set an empty string.
    $publisher_product = '';
    if( isset( $options['publisher_product'] ) ) {
        $publisher_product = $options['publisher_product'];
    } // end if
     
    // Render the output
    echo '<input type="text" id="publisher_product" name="gtm_display_options[publisher_product]" value="' . $publisher_product . '" />';
     
} // end gtm_publisher_product_callback

// Callback for quantcast Input
function gtm_quantcast_callback( $args ) {

    $options = get_option( 'gtm_display_options' );
     
    // we need to make sure the element is defined in the options. If not, we'll set an empty string.
    $quantcast = '';
    if( isset( $options['quantcast'] ) ) {
        $quantcast = $options['quantcast'];
    } // end if
     
    // Render the output
    echo '<input type="text" id="quantcast" name="gtm_display_options[quantcast]" value="' . $quantcast . '" />';
     
} // end gtm_quantcast_callback

// Callback for comscore Input
function gtm_comscore_callback( $args ) {

    $options = get_option( 'gtm_display_options' );
     
    // we need to make sure the element is defined in the options. If not, we'll set an empty string.
    $comscore = '';
    if( isset( $options['comscore'] ) ) {
        $comscore = $options['comscore'];
    } // end if
     
    // Render the output
    echo '<input type="text" id="comscore" name="gtm_display_options[comscore]" value="' . $comscore . '" />';
     
} // end gtm_comscore_callback
 
// Callback for Data Layer Checkbox
 function gtm_data_layer_callback( $args ) {
     
    $options = get_option( 'gtm_display_options' );

    //Check if exist
    $data_layer = '';
    if( isset( $options['data_layer'] ) ) {
        $data_layer = $options['data_layer'];
    } // end if
     
    $html = '<input type="checkbox" id="data_layer" name="gtm_display_options[data_layer]" value="1" ' . checked( 1, $data_layer, false ) . '/>'; 
    $html .= '<label for="data_layer"> '  . $args[0] . '</label>'; 
     
    echo $html;
     
} // end gtm_data_layer_callback

// Callback for Network Wide Checkbox
function gtm_network_wide_callback( $args ) {
     
    $options = get_option( 'gtm_display_options' );

    //Check if exist
    $network_wide = '';
    if( isset( $options['network_wide'] ) ) {
        $network_wide = $options['network_wide'];
    } // end if
     
    $html = '<input type="checkbox" id="network_wide" name="gtm_display_options[network_wide]" value="1" ' . checked( 1, $network_wide, false ) . '/>'; 
    $html .= '<label for="network_wide"> '  . $args[0] . '</label>'; 
     
    echo $html;
     
} // end gtm_network_wide_callback

// Sanitize inputs
function gtm_sanitize_options( $input ) {
     
    // Define the array for the updated options
    $output = array();
 
    // Loop through each of the options sanitizing the data
    foreach( $input as $key => $val ) {
     
        if( isset ( $input[$key] ) ) {
            $output[$key] = strip_tags( stripslashes( $input[$key] ) ) ;
        } // end if 
     
    } // end foreach
     
    // Return the new collection
    return apply_filters( 'gtm_sanitize_options', $output, $input );
 
} // end gtm_sanitize_options

// ######## Plugin Settings Ends ##########


// Declaring the display options to access them in functions.
$display_options = get_option( 'gtm_display_options' ); 

// Function plugins
function get_data() {
    if( is_multisite() ){
        //Grab the multi site instance path name (see Network level)
        $multi_path = get_blog_details( $GLOBALS['blog_id'] );

        //Remove slashes, store as var for DFM Data row retrieval
        $re_site = str_replace( "/", "", $multi_path->path );
    }

	$dfm_data_array = array();

	//WP Values
	//Checking if is 404 
	if ( is_404() ) {
	    $dfm_data_array['error'] = "404";
	} else {
        $dfm_data_array['error'] = null;
    }

	// Get Taxonomy
	function get_taxonomy_post() {
		if ( is_single() ){
			$category = get_the_category();
			$parent = get_cat_name( $category[0]->parent );
			if ( $parent ){
				$cat1 = $parent;
                $cat2 = $category[0]->name;
			} else {
				$cat1 = $category[0]->name;
                $cat2 = '';
			}
            return array($cat1,$cat2);
		}

	}
    $taxonomies = get_taxonomy_post();
	$dfm_data_array['Taxonomy1'] = $taxonomies[0];
    $dfm_data_array['Taxonomy2'] = $taxonomies[1];

	// Get section type
	function get_section_type() {
		if ( is_search() ) {
		$section_type = "Search";
		}elseif( is_single() ){
            $section_type = "Post";
		}elseif( is_home() ){
			$section_type = "Home";
		}elseif( is_category() ){
			$section_type = "Category";
		} else {
            $section_type = "Other";
        }
		return $section_type;
	}
	$dfm_data_array['sectionType'] = get_section_type();

	// Get section name
	function get_section_name() {
		if ( is_search() ) {
		$section_name = "Search";
		}elseif( is_single() ){
			$section_name = get_taxonomy_post()[0];
		}elseif( is_home() ){
			$section_name = "Home";
		}elseif( is_category() ){
			$section_name = single_cat_title('', false);
		} elseif ( is_page() ){
            $section_name = get_the_title();
        } else {
            $section_name = "Other";
        }
		return $section_name;
	}
	$dfm_data_array['sectionName'] = get_section_name();

	//Get GTM values on any site of the network.
	function get_gtm_values() {
		if ( is_multisite() ){
			switch_to_blog( 1 );
			$display_options = get_option( 'gtm_display_options' );
				//if it's multisite and you want to populate your values network wide this will change your blod id to the network admin blog id
				if ( isset( $display_options[ 'network_wide' ] ) ) {
					// Do something on blog 1, always network admin.
					$display_options_network = get_option( 'gtm_display_options' );
					$google_ua = esc_attr( $display_options_network['google_ua'] );
					$publisher_product = esc_attr( $display_options_network['publisher_product'] );
					$quantcast = esc_attr( $display_options_network['quantcast'] );
					$comscore = esc_attr( $display_options_network['comscore'] );
                    $data_layer = esc_attr( $display_options_network['data_layer'] );
            restore_current_blog();
				} else {
					//if it's multisite but the admin network don't want to populate the network level values
            restore_current_blog();
					$display_options = get_option( 'gtm_display_options' );
					$google_ua = esc_attr( $display_options['google_ua'] );
					$publisher_product = esc_attr( $display_options['publisher_product'] );
					$quantcast = esc_attr( $display_options['quantcast'] );
					$comscore = esc_attr( $display_options['comscore'] );
                    $data_layer = esc_attr( $display_options['data_layer'] );
				}
		} else {
			// If is not multisite show the single site values
			$display_options = get_option( 'gtm_display_options' );
			$google_ua = esc_attr( $display_options['google_ua'] );
			$publisher_product = esc_attr( $display_options['publisher_product'] );
			$quantcast = esc_attr( $display_options['quantcast'] );
			$comscore = esc_attr( $display_options['comscore'] );
            $data_layer = esc_attr( $display_options['data_layer'] );
		}
		return compact( 'google_ua', 'publisher_product', 'quantcast', 'comscore', 'data_layer', 'network_wide' );		
	}
	$gtmvalues = get_gtm_values();

	// Get KV Value	
	function get_kv_value() {
		if ( is_home()	) {
			$category = 'home';
		}
		elseif ( is_single() ) {
            $cat = get_the_category(); 
            //if this category is parent, the result will be 0
            if( $cat[0]->category_parent == 0 ) {
                $category = $cat[0]->cat_name;
            } else {
                    // if the current category is not parent, will print the parent name
                $category = get_cat_name( $cat[0]->category_parent );
            }
		} elseif ( is_search() ) {
		     $category = "Search";
        } else {
             $category = "Other";
        }
        return $category;
	}
	$dfm_data_array['kv'] = get_kv_value();

    function get_article_author() {
        if ( is_single() ) {
            global $post;
            if ( function_exists( 'get_coauthors' ) && count( get_coauthors( get_the_id() ) ) >= 1 ) {
                $coauthors = get_coauthors();
                $not_guest = false;
                foreach ($coauthors as $coauthor) {
                    $not_guest = ($coauthor->type == 'wpuser') ? true : false;
                }
                return ($not_guest == false) ? $coauthors[0]->display_name : get_the_author_meta( 'display_name' );
            } else {
                return get_the_author_meta( 'display_name' );
            }
        }
    }
    
	//Function to clean URLs
	function cleanURL( $x ) {
  		 return implode('/', array_slice(explode('/', preg_replace('/https?:\/\/|www./', '', $x)), 0, 1));
	}

	// WP Variables
	$dfm_data_array['currentDomain'] =  cleanURL(get_site_url());
	//$dfm_data_array['siteDomain'] =  cleanURL(get_site_url());
	$dfm_data_array['siteName'] = get_bloginfo('name');
	$dfm_data_array['pageTitle'] = get_the_title(); 
    $dfm_data_array['byline'] = get_article_author();

	//GTM Variables
    $dfm_data_array['google_ua'] = $gtmvalues['google_ua'];
    $dfm_data_array['publisher_product'] = cleanURL( $gtmvalues['publisher_product'] );
    $dfm_data_array['quantcast'] = $gtmvalues['quantcast'];
    $dfm_data_array['comscore'] = $gtmvalues['comscore'];
    $dfm_data_array['data_layer'] = $gtmvalues['data_layer'];

    return $dfm_data_array;
}
	function enqueue_dfm_core_level_1() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'dfm-core-level-1.js', 'http://local.digitalfirstmedia.com/common/dfm/assets/js/dfm-core-level1.min.js', array(), '1.0', false );
	}

add_action( 'wp_enqueue_scripts', 'enqueue_dfm_core_level_1' );

function inject_dfm_api(){
    
    $display_options = get_option( 'gtm_display_options' );
    if ( empty ( $display_options ) ) {
        return false;
    }

    $dfm_data_array = get_data();
    $dfm_api_html = '
    <script type="text/javascript" src="http://local.denverpost.com/common/dfm/dfm-core.js"></script>
    <!-- DFM API -->
    <script type="text/javascript">
        dfm.api("data","siteId",            "");
        dfm.api("data","company",           "");
        dfm.api("data","siteName",          "'.esc_js( $dfm_data_array['siteName'] ).'");
        dfm.api("data","siteDomain",        "'.$dfm_data_array['publisher_product'].'");
        dfm.api("data","currentDomain",     "'.$dfm_data_array['currentDomain'].'");
        dfm.api("data","contentId",         "");
        dfm.api("data","sectionName",       "'.esc_js( $dfm_data_array['sectionName'] ).'");
        dfm.api("data","pageID",            "");
        dfm.api("data","pageUrl",           "'.( $_SERVER['REQUEST_URI']).'");
        dfm.api("data","pageTitle",         "'.esc_js( $dfm_data_array['pageTitle'] ).'");
        dfm.api("data","sectionId",         "");
        dfm.api("data","keywords",          "");
        dfm.api("data","byline",            "'.esc_js($dfm_data_array['byline']).'");
        dfm.api("data","adTaxonomy",        "");
        dfm.api("data","Taxonomy1",         "'.esc_js( $dfm_data_array['Taxonomy1'] ).'");
        dfm.api("data","Taxonomy2",         "'.esc_js( $dfm_data_array['Taxonomy2'] ).'");
        dfm.api("data","Taxonomy3",         "");
        dfm.api("data","Taxonomy4",         "");
        dfm.api("data","kv",                "'.esc_js( $dfm_data_array['kv'] ).'");
        dfm.api("data","googleUA",          "'.$dfm_data_array['google_ua'].'");
        dfm.api("data","contentGeography",  "");
        dfm.api("data","contentRegion",     "");
        dfm.api("data","contentPostalCode", "");
        dfm.api("data","contentPrice",      "");
        dfm.api("data","contentChannel",    "");
        dfm.api("data","contentType",       "");
        dfm.api("data","contentCity",       "");
        //Extra
        dfm.api("data","errorType",         "'.$dfm_data_array['error'].'");
        dfm.api("data","pageType",          "");
        dfm.api("data", "quantcast",        "'.$dfm_data_array['quantcast'].'");
        dfm.api("data", "comscore",         "'.$dfm_data_array['comscore'].'");
    </script>
    ';

    //Script
    $dfm_api_html .= '

		<!-- GTM Data Layer -->
		<script type="text/javascript">
		  var dfm_gtm_dataLayer = true;

		var dfm_gtm_publisherproduct = dfm.api("data","currentDomain");
		var dataLayer_gaua = dfm.api("data", "googleUA");
        var dataLayer_byline = dfm.api("data", "byline");
        var dataLayer_pageurl = dfm.api("data", "pageUrl");
        var dataLayer_pagetitle = dfm.api("data", "pageTitle");
		var dataLayer_digitalpublisher = dfm.api("data", "company");
		var dataLayer_sectionName = dfm.api("data","sectionName");
        var dataLayer_taxonomy1 = dfm.api("data","Taxonomy1");
		var dataLayer_taxonomy2 = dfm.api("data","Taxonomy2");
		var dataLayer_quantcast = dfm.api("data", "quantcast");
		var dataLayer_comscore = dfm.api("data", "comscore");

		  analyticsEvent = function() {};
		  analyticsSocial = function() {};
		  analyticsVPV = function() {};
		  analyticsClearVPV = function() {};
		  analyticsForm = function() {};
		  window.dataLayer = window.dataLayer || [];
		  (function() {
		    window.gaJsonData = window.gaJsonData || [];
		    var gaTempJson = {
		      "comscore": "",
		      "pubplatform": "WP",
		      "releaseversion": "",
		      "digitalpublisher": "",
		      "publisherstate": ""
		    };
		    gaJsonData.push(gaTempJson);
		    var data = gaJsonData[0];
		    var json = {
		      "gaua": function() {
		        return data.gaua;
		      },
		      "quantcast": function () {
		        return data.quantcast;
		      },
		      "comscore": function() {
		        return data.comscore;
		      },
		      "pubplatform": function() {
		        return data.pubplatform;
		      },
		      "releaseversion": function() {
		        return data.releaseversion;
		      },
		      "digitalpublisher": function() {
		        return data.digitalpublisher;
		      },
		      "publisherstate": function() {
		        return data.publisherstate;
		      }
		    };
		    /*
		      Error type
		    */
		     var dataLayer_error = dfm.api("data","errorType");
		    /*
		      ga_ua
		    */
		    //var dataLayer_gaua = json["gaua"]();
		    /*
		      quantcast
		    */
		    //var dataLayer_quantcast = json["quantcast"]();
		    /*
		      comscore
		    */
		    //var dataLayer_comscore = json["comscore"]();
		    /*
		      Release Version
		    */
		    var dataLayer_releaseversion = json["releaseversion"]();
		    /*
		      Digital Publisher
		    */
		    //var dataLayer_digitalpublisher = json["digitalpublisher"]();
		    /*
		      Platform
		    */
		    var dataLayer_pubplatform = json["pubplatform"]();
		    /*
		      Publisher State
		    */
		    var dataLayer_publisherstate = json["publisherstate"]();
		    // Push the values into the dataLayer
		    dataLayer.push({
		      "errorType":dataLayer_error,
		      "ga_ua":dataLayer_gaua,
		      "quantcast":dataLayer_quantcast,
		      "comscore":dataLayer_comscore,
		      "Release Version":dataLayer_releaseversion,
		      "Digital Publisher":dataLayer_digitalpublisher,
		      "Platform":dataLayer_pubplatform,
		      "Publisher State":dataLayer_publisherstate,
              "Section":datadataLayer_sectionName,
              "Taxonomy1":dataLayer_taxonomy1,
              "Taxonomy2":dataLayer_taxonomy2,
              "Byline":dataLayer_byline,
              "Content Title":dataLayer_pagetitle,
              "URL":dataLayer_pageurl,
              "Page Title":dataLayer_pagetitle,
		    });
		  }()); 
		</script>

		<!-- GA global -->
		<script type="text/javascript" src="http://local.medianewsgroup.com/common/dfm/ga-datalayer.js"></script>';

    //Condition to check if the data_layer value is activated, then print it
    if ( $dfm_data_array['data_layer'] ) {
        echo $dfm_api_html;
    }

    $dfm_gtm = '
        <!-- Google Tag Manager dfm -->
        <noscript>
            <iframe src="//www.googletagmanager.com/ns.html?id=GTM-TLFP4R" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>';

    $dfm_gtm .= "
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TLFP4R');</script>
        <!-- End Google Tag Manager -->";

    echo $dfm_gtm;
}

// Inject the data layer and DFM-API only if the user has selected that option.
add_action('wp_head', 'inject_dfm_api');

?>
