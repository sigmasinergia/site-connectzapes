/* Sem JQuery */
document.addEventListener('DOMContentLoaded', function () {

    var wppMain = document.querySelector('.floating-wpp');

    // Dessa maneira passa a mensagem para url do WhatsApp
    var wppHtml = '<div class="floating-wpp-button">';
    wppHtml += '        <div class="floating-wpp-button-image">';
    wppHtml += '            <img src="assets/wp/whatsapp.svg" alt="whatsapp ConnectZap">';
    wppHtml += '        </div>';
    wppHtml += '    </div>';
    wppHtml += '    <div id="WAContent" class="floating-wpp-popup">';
    wppHtml += '        <div class="floating-wpp-head">';
    wppHtml += '            <span>¡Habla con ConnectZap!</span>';
    wppHtml += '            <strong class="close">×</strong>';
    wppHtml += '        </div>';
    wppHtml += '        <div style="z-index: -1;">';
    wppHtml += '            <div class="floating-wpp-message">¿Cómo podemos ayudar?</div>';
    wppHtml += '            <div class="floating-wpp-input-message"><textarea id="textWP"></textarea>';
    wppHtml += '                <div class="floating-wpp-btn-send">';
    wppHtml += '                    <a href="#">';
    wppHtml += '                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate" viewBox="0 0 20 18" width="20" height="18"><defs><clipPath id="_clipPath_fgX00hLzP9PnAfCkGQoSPsYB7aEGkj1G"><rect width="20" height="18"></rect></clipPath></defs><g clip-path="url(#_clipPath_fgX00hLzP9PnAfCkGQoSPsYB7aEGkj1G)"><path d=" M 0 0 L 0 7.813 L 16 9 L 0 10.188 L 0 18 L 20 9 L 0 0 Z " fill="rgb(46,46,46)"></path></g></svg>';
    wppHtml += '                    </a>';
    wppHtml += '                </div>';
    wppHtml += '            </div>';
    wppHtml += '        </div>';
    wppHtml += '    </div>';
    wppMain.innerHTML = wppHtml;
    
    var wppButton = document.querySelector('.floating-wpp-button');
    var wppPopup = document.querySelector('.floating-wpp-popup');
    var closeButton = document.querySelector('.floating-wpp-head .close');    

    setTimeout(function () {
        if (wppMain) {
            wppMain.style.display = 'block';
        }
    }, 1000);

    if (wppButton && wppPopup && closeButton) {
        wppButton.addEventListener('click', function () {
            wppPopup.classList.toggle('active');
        });

        closeButton.addEventListener('click', function () {
            wppPopup.classList.remove('active');
        });
    }


    /* Botão Enviar Mensagem para WhatsApp */
    var wppSendButtons = document.querySelectorAll('.floating-wpp-btn-send');
    wppSendButtons.forEach(function (button) {
        
        button.addEventListener('click', function () {
            var wppText = document.getElementById('textWP');
            if(wppText.value?.trim() !== ''){
                //document.location.href='https://web.whatsapp.com/send?phone=+5521981587295&text=' + wppText.value.trim();    
                document.location.href='https://wa.me/+5521981587295?text=' + wppText.value.trim();    
            }
        });

    });        
        

});
