$(function() {                     // When the page has loaded,
  $('nav').waypoint(               // create a waypoint
    function() {
      alert("Waypoint reached.");
    }
  )
});