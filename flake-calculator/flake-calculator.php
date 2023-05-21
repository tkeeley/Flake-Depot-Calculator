<?php

/*
Plugin Name: Flake Calculator
Description: A simple calculator for determining the amount of flake needed based on square footage.
Version: 1.0
Author: Cup O Code
Author URI: https://cupocode.com
*/

function flake_calculator_enqueue_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');

    // Enqueue Bootstrap JS and jQuery
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', array(), null, true);
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'flake_calculator_enqueue_scripts');


function flake_calculator_shortcode() {
 	$logo = get_custom_logo();
    $output =  '
	<style>
		#calculator {
			max-width: 500px;
            text-align: left;
			margin: 0 auto;
			background-color: #f5f5f5;
			padding: 30px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
		}
		h1 {
			font-size: 2em !important;
			font-weight: bold;
			text-align: center;
			margin-bottom: 20px;
		}
		label {
			font-weight: bold;
		}
		.output-section {
			margin-top: 20px;
            padding: 0 10px;
            font-size: 1.2em;
            border: 1px solid #000;
            border-radius: 7px;;
            background-color: #fff;
		}
		.output-section label {
			margin-right: 10px;
            width: 100%;
            font-size: 1em;
            background-color: transparent;
            text-align: left;
		}
		#amountNeeded {
			font-size: 1.8em;
            padding-left: 7px;
            font-weight: bold;
            margin-bottom: 10px;
            width: 100%;
		}
		#recommendedOrdering {
			margin-top: 30px;
			font-size: 18px;
			font-weight: bold;
			text-align: left;
		}
        .form-control {
            border: none;
            color: #000;
        }
        .form-control[readonly] {
            background-color: inherit;
        }

        #boxesNeeded,  #poundsNeeded{
            font-size: 1.1em;
          }
	</style>

	<div class="container mt-5" id="calculator">
    ' . $logo . '
		<h1>Flake Calculator</h1>
        <p style="text-align:center;">*Calculations for 1/4&quot; Flake</p>
		<form action="#" method="GET">
			<div class="form-group">
				<label for="squareFeet">Square Feet</label>
				<input type="number" class="form-control" id="squareFeet" placeholder="Enter square feet">
			</div>

			<div class="form-group">
				<div class="card bg-light">
					<div class="card-body">
						<h5 class="card-title">Pounds Needed</h5>
						<p class="card-text"><input type="text" id="amountNeeded" readonly></p>
					</div>
				</div>
			</div>
            <div id="recommendedOrdering">Recommended Ordering Quantity</div>
			<div class="output-section">
                <input type="text" class="form-control" id="boxesNeeded" readonly>
				<label for="boxesNeeded" id="boxesLabel">40 Pound boxes:</label>
			</div>

			<div class="output-section">
                <input type="text" class="form-control" id="poundsNeeded" readonly>
				<label for="poundsNeeded">Total Pounds Purcased</label>
			</div>
		</form>
	</div>    
	<script>
const queryParamKey = "numBoxesNeeded";

function updateURLParameter(url, param, value) {
  const urlObj = new URL(url);
  urlObj.searchParams.set(param, value);
  return urlObj.toString();
}

function updateFormField() {
  const urlParams = new URLSearchParams(window.location.search);
  const queryParamValue = urlParams.get(queryParamKey);

  if (queryParamValue) {
    const formField = document.querySelector(".num-box-field input");
    if (formField) {
      formField.value = queryParamValue;
      if (window.Forminator && window.Forminator.Forms) {
        Forminator.Forms.triggerChangeEvent(formField);
      }
    }
  }
}

window.addEventListener("DOMContentLoaded", function() {
  const squareFeetInput = document.getElementById("squareFeet");
  const amountNeededOutput = document.getElementById("amountNeeded");
  const boxesLabel = document.getElementById("boxesLabel");
  const boxesNeededOutput = document.getElementById("boxesNeeded");
  const poundsNeededOutput = document.getElementById("poundsNeeded");

  function calculateAndDisplay() {
    const squareFeet = parseFloat(squareFeetInput.value);

    if (isNaN(squareFeet)) {
      amountNeededOutput.value = "";
      boxesNeededOutput.value = "";
      poundsNeededOutput.value = "";
      return;
    }

    const amountNeeded = Math.ceil((squareFeet / 1250) * 208);
    const boxesNeeded = Math.ceil(amountNeeded / 40);

    if (boxesNeeded > 1) {
      boxesLabel.innerHTML = "40 pound box" + (boxesNeeded > 1 ? "es" : "") + ":";
    } else {
      boxesLabel.textContent = "40 pound box:";
    }

    const poundsNeeded = boxesNeeded * 40;

    amountNeededOutput.value = amountNeeded;
    boxesNeededOutput.value = boxesNeeded;
    poundsNeededOutput.value = poundsNeeded;

    const currentURL = window.location.href;
    const updatedURL = updateURLParameter(currentURL, queryParamKey, boxesNeeded);
    history.replaceState(null, "", updatedURL);
    updateFormField();
  }

  squareFeetInput.addEventListener("input", calculateAndDisplay);
  calculateAndDisplay();

  // Initialize the form field value on page load and when the query parameter changes
  updateFormField();
});

</script>
';
    return $output;
}

function flake_calculator_init() {
    function flake_calculator_shortcode_func() {
        return flake_calculator_shortcode();
    }
    add_shortcode('flake_calculator', 'flake_calculator_shortcode_func');
}
add_action('init', 'flake_calculator_init');
?>