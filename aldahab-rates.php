<?php
/*
Plugin Name: Al Dahab Rates
Description: Currency Exchange Rates Plugin
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

// Admin Menu
add_action('admin_menu', 'aldahab_admin_menu');

function aldahab_admin_menu() {
    add_menu_page(
        'Exchange Rates',
        'Exchange Rates',
        'manage_options',
        'aldahab-rates',
        'aldahab_rates_page',
        'dashicons-money-alt',
        25
    );
}

// Register Settings
add_action('admin_init', 'aldahab_register_settings');

function aldahab_register_settings() {

register_setting('aldahab_group', 'aldahab_rates');

$currencies = ['INR','PKR','NPR','PHP','LKR','EUR','GBP','JPY','HKD','AUD','CAD','THB','SGD','UGX','KSH','EGP'];

add_settings_section('main_section', '', null, 'aldahab-rates');

foreach($currencies as $currency){

add_settings_field(
$currency,
"AED to $currency",
function() use ($currency){

$options = get_option('aldahab_rates');
$value = isset($options[$currency]) ? $options[$currency] : '';

echo "<input type='text' name='aldahab_rates[$currency]' value='$value' style='width:150px;'>";

},
'aldahab-rates',
'main_section'
);

}

}

// Admin Page
function aldahab_rates_page(){
?>
<div class="wrap">
<h1>Daily Exchange Rates</h1>

<form method="post" action="options.php">

<?php
settings_fields('aldahab_group');
do_settings_sections('aldahab-rates');
submit_button('Save Rates');
?>

</form>
</div>
<?php
}

// Frontend Shortcode
add_shortcode('aldahab_rates', 'aldahab_show_rates');

function aldahab_show_rates(){

$options = get_option('aldahab_rates');

$output = '<div class="rate-grid">';

if($options){

foreach($options as $currency => $rate){

$output .= '
<div class="rate-card">
<h3>AED to '.$currency.'</h3>
<p>'.$rate.'</p>
</div>
';

}

}

$output .= '</div>';

return $output;

}

// Load CSS
add_action('wp_enqueue_scripts', function(){
wp_enqueue_style('aldahab-style', plugin_dir_url(__FILE__) . 'style.css');
});