jQuery(document).ready(function($) {
    $('#check-btn').on('click', function() {
        var $btn = $(this);
        var $status = $('#status');
        
        $btn.prop('disabled', true).text('Kontrol ediliyor...');
        $status.removeClass('success error').html('⏳ Bağlantı kontrol ediliyor...').show();
        
        $.ajax({
            url: agentx_ajax.url,
            type: 'POST',
            data: {
                action: 'agentx_check',
                nonce: agentx_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $status.html('✅ ' + response.data).addClass('success');
                } else {
                    $status.html('❌ ' + response.data).addClass('error');
                }
            },
            error: function() {
                $status.html('❌ Sunucu hatası oluştu').addClass('error');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Bağlantıyı Kontrol Et');
            }
        });
    });
});
