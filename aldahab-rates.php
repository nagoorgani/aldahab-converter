<?php
/*
Plugin Name: Al Dahab Currency Converter
Description: AED Currency Converter Plugin
Version: 2.0
Author: Nagoorgani
*/

if (!defined('ABSPATH')) exit;

/* ADMIN MENU */
add_action('admin_menu', function () {
    add_menu_page(
        'Currency Rates',
        'Currency Rates',
        'manage_options',
        'aldahab-rates',
        'aldahab_admin_page',
        'dashicons-money-alt',
        25
    );
});

/* SETTINGS */
add_action('admin_init', function () {

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

echo "<input type='text' name='aldahab_rates[$currency]' value='$value' />";

},
'aldahab-rates',
'main_section'
);

}

});

/* ADMIN PAGE */
function aldahab_admin_page(){ ?>
<div class="wrap">
<h1>Update Currency Rates</h1>
<form method="post" action="options.php">
<?php
settings_fields('aldahab_group');
do_settings_sections('aldahab-rates');
submit_button('Save Rates');
?>
</form>
</div>
<?php }

/* SHORTCODE */
add_shortcode('aldahab_converter', function(){

$options = get_option('aldahab_rates');
$json = json_encode($options);

ob_start(); ?>

<div class="aldahab-box">

<h2>Currency Converter</h2>

<div class="tabs">
<button class="tab active" data-type="transfer">Transfer Rate</button>
<button class="tab" data-type="cash">Cash Rate</button>
</div>

<label>AMOUNT YOU WILL SEND</label>
<div class="row">
<input type="number" id="sendAmount" value="1000">
<span>AED</span>
</div>

<label>Select Currency</label>
<select id="currency">
<?php foreach($options as $code => $rate){ ?>
<option value="<?php echo $code; ?>"><?php echo $code; ?></option>
<?php } ?>
</select>

<label>RECEIVER WILL GET</label>
<div class="result" id="result">0</div>

<p class="note">(Rates are indicative only and subject to change at any time.)</p>

</div>

<script>
var aldahabRates = <?php echo $json; ?>;
</script>

<?php return ob_get_clean(); });

/* CSS JS */
add_action('wp_enqueue_scripts', function(){
wp_enqueue_style('aldahab-style', plugin_dir_url(__FILE__).'style.css');
wp_enqueue_script('aldahab-js', plugin_dir_url(__FILE__).'script.js', [], false, true);
});