$(function()
{
   function timeChecker()
      {
        setInterval(function(){
         var storedTimeStamp = sessionStorage.getItem("lastTimeStamp");
         timeCompare(storedTimeStamp);
        },10000);
      }

      function timeCompare(timeString){
         var currentTime   = new Date();
         var pastTime      = new Date(timeString);
         var timeDiff      = currentTime - pastTime;
         var minPast       = Math.floor( (timeDiff/60000) );

         if (minPast > 5){
            sessionStorage.removeItem('lastTimeStamp');
            window.location = "../logout.php";
            return false
         }
         
      }
   
   $(document).mousemove(function(){
      var timeStamp = new Date();
      sessionStorage.setItem('lastTimeStamp',timeStamp);
   });

   timeChecker()
});
