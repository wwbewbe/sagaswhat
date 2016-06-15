/*-------------------------------------------*/
/*  位置情報取得
/*-------------------------------------------*/
jQuery(document).ready(function(jQuery){
    function get_gps(){
        if (navigator.geolocation) {
            // Geolocationが使える場合
            // 現在の位置情報を取得
            navigator.geolocation.getCurrentPosition(
                // （1）位置情報取得に成功したとき
                function (position) {
                        lat = position.coords.latitude;
                        lng = position.coords.longitude;
                        // 緯度・経度をURLパラメータに追加
                        location.search = "?lat=" + lat + "&lng=" + lng;
                        //location.href="?lat=" + lat + "&lng=" + lng;
                },
                // （2）位置情報の取得に失敗した場合
                function (error) {
                    window.alert("The service could not get your location.");
                } // function (error)
            );
        } else {
            // Geolocationが使えない場合
            window.alert("You can not use this function, because this browser could not get your location.");
        }
    } // get_gps
    // ボタンがクリックされたら
    jQuery("#nearnav").click(function(){
        // 位置情報取得の処理を実行
        get_gps();
   });
});
