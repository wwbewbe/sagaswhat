<script type="text/javascript">
jQuery(document).ready(function($){
    if (navigator.geolocation) {
        //Geolocationが使える場合、現在の位置情報を取得
        navigator.geolocation.getCurrentPosition(
            //位置情報取得に成功
            function (position) {
                    var lat= position.coords.latitude;
                    var lng= position.coords.longitude;
					// 緯度・経度をURLパラメータに追加
					location.search = "?lat=" + lat + "&lng=" + lng;
            },
            //位置情報の取得に失敗
            function (error) {
				var msg = "<?php echo esc_html__('The service could not get your location.', 'SagasWhat'); ?>";
                window.alert(msg);
            } // function (error)
        );
    } else {
        // Geolocationが使えない場合
		var msg = "<?php echo esc_html__('You can not use this function, because this browser could not get your location.', 'SagasWhat'); ?>";
        window.alert(msg);
    }
});
</script>
