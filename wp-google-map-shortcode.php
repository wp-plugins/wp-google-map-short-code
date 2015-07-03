<?php
/*
Plugin Name: Wp Google Map Short Code
Plugin URI: http://odrasoft.com
Description: Adds  Google Maps using the  short code .
Version: 1.0
Author: swadesh swain
Author URI: http://odrasoft.com
Contributors: swadeshswain
*/



function google_map_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'address'           => false,
			'scrollwheel' => 'true',
			'zoom'              => false,
			'lat'               => false,
			'long'              => false,
			'desc'              => false,
			'icon'              => false,
			'width'             => '100%',
			'height'            => '400px',
			'mapcontrols'   => 'false'
		),
		$atts
	);

	$address = $atts['address'];
	$zoom = $atts['zoom'];
	$desc = $atts['desc'];
	$icon = $atts['icon'];
	$lat = $atts['lat'];
	$long = $atts['long'];

	if( $address !="" )
	{
		if( $address && wp_script_is( 'google-map-script-api', 'registered' ) ) {

		wp_print_scripts( 'google-map-script-api' );

		$coordinates = google_map_get_coordinates( $address );

		if( !is_array( $coordinates ) )
			return;

		$map_id = uniqid( 'google_map_' ); // generate a unique ID for this map

		ob_start(); ?>
		<div class="google_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr( $atts['height'] ); ?>; width: <?php echo esc_attr( $atts['width'] ); ?>"></div>
	    <script type="text/javascript">
			var map_<?php echo $map_id; ?>;
			function pw_run_map_<?php echo $map_id ; ?>(){
				var location = new google.maps.LatLng("<?php echo $coordinates['lat']; ?>", "<?php echo $coordinates['lng']; ?>");
				var map_options = {
					zoom: <?php if ($zoom!=""){ echo $zoom; } else { echo "15"; } ?>,
					center: location,
					scrollwheel: <?php echo 'true' === strtolower( $atts['scrollwheel'] ) ? '1' : '0'; ?>,
					disableDefaultUI: <?php echo 'true' === strtolower( $atts['mapcontrols'] ) ? '1' : '0'; ?>,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				var map_<?php echo $map_id ; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id ; ?>"), map_options);
				var marker = new google.maps.Marker({
				position: location,
				map: map_<?php echo $map_id ; ?>,
				icon: "<?php echo $icon ; ?>",
				title:"<?php echo $desc ; ?>"
				});
				<?php if($desc !="") {?>
				var infowindow = new google.maps.InfoWindow({
			    content: "<?php echo $desc ; ?>"
			    });
			    google.maps.event.addListener(marker, "click", function() {
				infowindow.open(map_<?php echo $map_id ; ?>, marker);
			    });
			   <?php  } ?>
			}
			pw_run_map_<?php echo $map_id ; ?>();
		</script>
		<?php 
		return ob_get_clean();
		}
	}
	elseif( $lat!="" && $long!="" && wp_script_is( 'google-map-script-api', 'registered' ) ){
	wp_print_scripts( 'google-map-script-api' );
		$map_id = uniqid( 'google_map_' ); // generate a unique ID for this map

		ob_start(); ?>

	   <div class="google_map_canvas" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr( $atts['height'] ); ?>; width: <?php echo esc_attr( $atts['width'] );       ?>"></div>
	    <script type="text/javascript">
			var map_<?php echo $map_id; ?>;
			function pw_run_map_<?php echo $map_id ; ?>(){
				var location = new google.maps.LatLng("<?php echo $lat; ?>", "<?php echo $long; ?>");
				var map_options = {
					zoom: <?php if ($zoom!=""){ echo $zoom; } else { echo "15"; } ?>,
					center: location,
					scrollwheel: <?php echo 'true' === strtolower( $atts['scrollwheel'] ) ? '1' : '0'; ?>,
					disableDefaultUI: <?php echo 'true' === strtolower( $atts['mapcontrols'] ) ? '1' : '0'; ?>,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				var map_<?php echo $map_id ; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id ; ?>"), map_options);
				var marker = new google.maps.Marker({
				position: location,
				map: map_<?php echo $map_id ; ?>,
				icon: "<?php echo $icon ; ?>",
				title:"<?php echo $desc ; ?>"
				});
				<?php if($desc !="") {?>
				var infowindow = new google.maps.InfoWindow({
			    content: "<?php echo $desc ; ?>"
			    });
			    google.maps.event.addListener(marker, "click", function() {
				infowindow.open(map_<?php echo $map_id ; ?>, marker);
			    });
			   <?php  } ?>
			}
			pw_run_map_<?php echo $map_id ; ?>();
		</script>	
<?php	
    return ob_get_clean();
	}
	else
	{
	
return __( 'Please check your latitude and longitude OR address in shortcode.', 'google_map' );
	}
}
function google_map_get_coordinates( $address, $force_refresh = false ) {

    $address_hash = md5( $address );

    $coordinates = get_transient( $address_hash );

    if ($force_refresh || $coordinates === false) {

    	$args       = array( 'address' => urlencode( $address ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );

     	if( is_wp_error( $response ) )
     		return;

     	$data = wp_remote_retrieve_body( $response );

     	if( is_wp_error( $data ) )
     		return;

		if ( $response['response']['code'] == 200 ) {

			$data = json_decode( $data );

			if ( $data->status === 'OK' ) {

			  	$coordinates = $data->results[0]->geometry->location;

			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $data->results[0]->formatted_address;

			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;

			} elseif ( $data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'google_map' );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'google_map' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'google_map' );
			}

		} else {
		 	return __( 'Unable to contact Google API service.', 'google_map' );
		}

    } else {
       // return cached results
       $data = $coordinates;
    }

    return $data;
}

function google_map_css() {
	?>
<style type="text/css">
.google_map_canvas img {
	max-width: none;
}</style>
<?php
}
function google_map_load_scripts() {
	wp_register_script( 'google-map-script-api', '//maps.google.com/maps/api/js?sensor=false' );
}
add_action( 'wp_enqueue_scripts', 'google_map_load_scripts' );
add_action( 'wp_head', 'google_map_css' );
add_shortcode( 'google_map', 'google_map_shortcode' );
?>