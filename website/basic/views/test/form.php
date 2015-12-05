<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Report */
/* @var $form ActiveForm */
$this->title = "Form";
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var geocoder = new google.maps.Geocoder();
</script>
<script>
function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address);
    } else {
      updateMarkerAddress('Cannot determine address at this location.');
    }
  });
}

function updateMarkerPosition(latLng) {
  document.getElementById('report-long').value =  latLng.lng();
  document.getElementById('report-lat').value =  latLng.lat();
}

function updateMarkerAddress(str) {
  document.getElementById('report-location_prose').value = str;
}

function initialize() {
  var latLng = new google.maps.LatLng(10.0, 25.0);
  var map = new google.maps.Map(document.getElementById('map'), {
    center: latLng,
    zoom: 3,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  })

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });


  var marker = new google.maps.Marker({
    position: latLng,
    title: 'Point A',
    map: map,
    draggable: true
  });

  // Update current position info.
  updateMarkerPosition(latLng);
  geocodePosition(latLng);

  // Add dragging event listeners.
  google.maps.event.addListener(marker, 'drag', function() {
    updateMarkerPosition(marker.getPosition());
  });

  google.maps.event.addListener(marker, 'dragend', function() {
    geocodePosition(marker.getPosition());
  });
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<style>
#mapCanvas {
    width: 100%;
    height: 400px;
}
</style>
<div class="test-form">
	<div class="report-form">
    <?php $form = ActiveForm::begin(); ?>

        <div id="form_get_lat_long">
            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
            <div id="mapCanvas"></div>
            <?= $form->field($model, 'lat') ?>
            <?= $form->field($model, 'long') ?>
        </div>
        <?= $form->field($model, 'requires_editing') ?>
        <?= $form->field($model, 'is_solved') ?>
        <?= $form->field($model, 'id_language')->widget(Select2::classname(), [
            'data' => $languages,
        ]) ?>
        <?= $form->field($model, 'age') ?>
        <?= $form->field($model, 'problem_category')->widget(Select2::classname(), [
            'data'=>$problems,
          ]) ?>
        <?= $form->field($model, 'disability_category')->widget(Select2::classname(), [
            'data'=>$disabilities,
        ]) ?>
        <?= $form->field($model, 'location_prose') ?>
        <?= $form->field($model, 'problem_prose') ?>
        <?= $form->field($model, 'editor_comments') ?>
        <?= $form->field($model, 'gender') ?>
        <?= $form->field($model, 'name') ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- test-form -->
