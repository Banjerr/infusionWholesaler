<script>
  function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

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

  var wholeParam = getParameterByName('wholeParam');

  var wholeCookie = getCookie('wholeCookie');

  if (wholeParam || wholeCookie != '') {
    document.cookie="wholeCookie=true;path=/";
  } else {
   location.assign('http://gstaging.getuwired.us/engconcepts/ben/wholesale_portal/proofOfConcept.php');
  }
</script>
