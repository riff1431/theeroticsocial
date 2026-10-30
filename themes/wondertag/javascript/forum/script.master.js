// +------------------------------------------------------------------------+
// | @author Arifur Rahman
// | @agency Huipper
// | @url https://huipper.com/
// | @github https://github.com/arifurfullstack
// +------------------------------------------------------------------------+
// | theeroticsocial - The Ultimate Social Networking Platform
// | Copyright (c) 2026 theeroticsocial. All rights reserved.
// +------------------------------------------------------------------------+

$(document).ready(function(){

      $(document).on("click",".show-forums",function(){
          $("div[data-slide=" +  $(this).attr("id")+"]").slideToggle("slow");
      });


      

 });
