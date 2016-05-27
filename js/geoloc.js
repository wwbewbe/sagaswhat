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
                        lat= position.coords.latitude;
                        lng= position.coords.longitude;
                        // [ドメイン]/neighborhood/~となる
                        location.href="/sagaswhat/neighborhood/?lat=" + lat + "&lng=" + lng;
                },
                // （2）位置情報の取得に失敗した場合
                function (error) {
                    window.alert("位置情報の取得ができませんでした。");
                } // function (error)
            );
        } else {
            // Geolocationが使えない場合
            window.alert("このブラウザでは位置情報が取得出来ないためご利用できません。");
        }
    } // get_gps
    // ボタンがクリックされたら
    jQuery("#neighbornav").click(function(){
        // 位置情報取得の処理を実行
        get_gps();
   });
});
