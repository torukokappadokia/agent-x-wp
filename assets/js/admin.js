jQuery(document).ready(function($) {
    $('#check-btn').on('click', function() {
        $('#status').html('Kontrol ediliyor...').removeClass();
        
        $.ajax({
            url: agentx_ajax.url,
            type: 'POST',
            data: {
                action: 'agentx_check',
                nonce: agentx_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#status').html('✅ ' + response.data).addClass('success');
                } else {
                    $('#status').html('❌ ' + response.data).addClass('error');
                }
            },
            error: function() {
                $('#status').html('❌ Sunucu hatası').addClass('error');
            }
        });
    });
});
