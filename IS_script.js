<script>
// get query params
function getParameterByName(name) {
name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

// get cookies
function getCookie(cname) {
var name = cname + "=";
var ca = document.cookie.split(';');
for(var i=0; i<ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1);
    if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
}
return "";
}

// params
var wholeParam = getParameterByName('wholeParam');
var wholeCookie = getCookie('wholeCookie');


if (wholeParam || wholeCookie != '') { // if there is a query param or the cookie is not empty, set a cookie
    document.cookie="wholeCookie=true;path=/";
} else { // if there is no cookie or query param then redirect back to the wholesale portal page
  location.assign('http://gstaging.getuwired.us/engconcepts/ben/wholesale_portal/proofOfConcept.php');
}

// change the "continue shopping" button links to link to the wholesale portal page
jQuery(function(){
    jQuery('.continueButton').attr('href', 'http://gstaging.getuwired.us/engconcepts/ben/wholesale_portal/proofOfConcept.php');
});
</script>
