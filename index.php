<?php
/*
Plugin Name: WP Forex Calculator
Plugin URI: http://www.analystcoder.com/
Description: Forex Position Size Calculator
Version: 1.1
Author: AnalystCoder
Author URI: http://www.analystcoder.com/
*/
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

if ( ! class_exists( 'ForexCalculator' ) ) {
  class ForexCalculator
  {
    protected $tag = 'wp-forex-calculator';
    protected $name = 'Forex Position Size Calculator';
    protected $version = '1.1';
    protected $options = array();

    public function __construct()
    {
      if ( $options = get_option( $this->tag ) ) {
        $this->options = $options;
      }
      add_shortcode('position_size_calculator', array( &$this, 'shortcode_position_size' ) );
    }

    public function shortcode_position_size( $atts, $content = null )
    {
      $this->_enqueue();
      ob_start();
      ?>
      
<div class="control-group">
  <label class="control-label" for="currency">Account Currency</label>
  <div class="controls">
      <select id="currency" name="currency" class="calculator-input">
          <option value="USD">USD</option>
          <option value="EUR">EUR</option>
          <option value="JPY">JPY</option>
          <option value="GBP">GBP</option>
          <option value="CHF">CHF</option>
          <option value="AUD">AUD</option>
          <option value="CAD">CAD</option>
          <option value="NZD">NZD</option>
      </select>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="balance">Account Balance</label>
  <div class="controls">
    <input type="text" id="balance" name="balance" class="calculator-input" />
  </div>
</div>

<div class="control-group" id="risk-row">
  <label class="control-label" for="risk">Risk Percentage</label>
  <div class="controls">
    <input type="text" id="risk" name="risk" class="calculator-input" />
    <input type="button" class="btn btn-default btn-tall" id="btn-swap-money" value="Swap with Money" style="margin-left: 8px;">
  </div>
</div>

<div class="control-group" id="riskmoney-row" style="display: none;">
  <label class="control-label" for="riskmoney">Money, <span id="risk-currency"></span></label>
  <div class="controls">
    <input type="text" id="riskmoney" name="riskmoney" class="calculator-input" />
    <input type="button" class="btn btn-default btn-tall" id="btn-swap-risk" value="Swap with Risk" style="margin-left: 8px;">
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="stoploss">Stop Loss in Pips</label>
  <div class="controls">
    <input type="text" id="stoploss" name="stoploss" class="calculator-input" />
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="currencypair">Currency Pair</label>
  <div class="controls">
    <select id="currencypair" name="currencypair" class="calculator-input">
      <option value="EUR/USD">EUR / USD</option>
      <option value="GBP/USD">GBP / USD</option>
      <option value="USD/CHF">USD / CHF</option>
      <option value="USD/CAD">USD / CAD</option>
      <option value="USD/JPY">USD / JPY</option>
      <option value="NZD/USD">NZD / USD</option>
      <option value="AUD/USD">AUD / USD</option>
      <option value="EUR/AUD">EUR / AUD</option>
      <option value="EUR/GBP">EUR / GBP</option>
      <option value="EUR/JPY">EUR / JPY</option>
      <option value="EUR/CAD">EUR / CAD</option>
      <option value="EUR/CHF">EUR / CHF</option>
      <option value="EUR/NZD">EUR / NZD</option>
      <option value="GBP/CAD">GBP / CAD</option>
      <option value="GBP/CHF">GBP / CHF</option>
      <option value="GBP/JPY">GBP / JPY</option>
      <option value="GBP/AUD">GBP / AUD</option>
      <option value="GBP/NZD">GBP / NZD</option>
      <option value="AUD/CAD">AUD / CAD</option>
      <option value="AUD/JPY">AUD / JPY</option>
      <option value="AUD/CHF">AUD / CHF</option>
      <option value="AUD/NZD">AUD / NZD</option>
      <option value="CHF/JPY">CHF / JPY</option>
      <option value="CAD/CHF">CAD / CHF</option>
      <option value="CAD/JPY">CAD / JPY</option>
      <option value="NZD/CHF">NZD / CHF</option>
      <option value="NZD/JPY">NZD / JPY</option>
      <option value="NZD/CAD">NZD / CAD</option>
    </select>
  </div>
</div>

<div class="control-group" id="exchange-rate-row" style="display: none;">
  <label class="control-label" for="exchange-rate">Price for <span id="exchange-rate-labels"></span></label>
  <div class="controls">
    <input type="text" id="exchange-rate" name="exchange-rate" class="calculator-input" />
  </div>
</div>

<div class="control-group" style="padding-top: 0.5em; padding-bottom: 2em;">
  <label class="control-label"></label>
  <div class="controls">
      <input type="button" class="btn btn-default btn-taller" id="btn-calculate" value="Calculate">
      <input type="reset" class="btn btn-primary btn-taller" value="Clear" style="margin-left: 12px;">
  </div>
</div>

<div class="control-group" id="result-risk-row" style="display: none;">
  <label class="control-label" for="percent-risk">Risk Percentage</label>
  <div class="controls">
    <input type="text" id="percent-risk" class="calculator-input" readonly />
  </div>
</div>

<div class="control-group" id="result-risk-amount-row">
  <label class="control-label" for="amount-risk">Amount at Risk</label>
  <div class="controls">
    <input type="text" id="amount-risk" class="calculator-input" readonly />
  </div>
</div>

<div class="control-group" id="">
  <label class="control-label" for=""position-size>Position Size</label>
  <div class="controls">
    <input type="text" id="position-size" class="calculator-input" readonly />
  </div>
</div>

<div class="control-group" id="">
  <label class="control-label" for="lots-standard">Standard Lots</label>
  <div class="controls">
    <input type="text" id="lots-standard" class="calculator-input" readonly />
  </div>
</div>

<div class="control-group" id="">
  <label class="control-label" for="lots-mini">Mini Lots</label>
  <div class="controls">
    <input type="text" id="lots-mini" class="calculator-input" readonly />
  </div>
</div>

<div class="control-group" id="">
  <label class="control-label" for="lots-micro">Micro Lots</label>
  <div class="controls">
    <input type="text" id="lots-micro" class="calculator-input" readonly />
  </div>
</div>

      <?php
      return ob_get_clean();
    }

    protected function _enqueue()
    {
      $plugin_path = plugin_dir_url( __FILE__ );
      if ( !wp_style_is( $this->tag, 'enqueued' ) ) {
        wp_enqueue_style(
          $this->tag,
          $plugin_path . 'css/style.css',
          array(),
          $this->version
        );
      }
      if ( !wp_script_is( $this->tag, 'enqueued' ) ) {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script(
          'numeral-' . $this->tag,
          $plugin_path . 'js/numeral.min.js',
          array( 'jquery' ),
          '1.5.3'
        );
        wp_enqueue_script(
          $this->tag,
          $plugin_path . 'js/calculator.js',
          array( 'jquery' ),
          $version
        );
      }
    }

  }
  new ForexCalculator;
}
