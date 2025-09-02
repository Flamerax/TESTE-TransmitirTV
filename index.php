<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Transmitir Vídeo para TV</title>
<script type="text/javascript" src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; background: #111; color: #fff; }
  button { padding: 10px 20px; font-size: 1rem; cursor: pointer; margin-top: 20px; }
</style>
</head>
<body>



<div style="position:relative;padding-top:56.25%;"><iframe src="https://iframe.mediadelivery.net/embed/487146/e7544879-6353-42cd-bcb2-31efec28371c?autoplay=true&loop=false&muted=false&preload=true&responsive=true" loading="lazy" style="border:0;position:absolute;top:0;height:100%;width:100%;" allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe></div>

<br>

<button id="castBtn">Transmitir para TV</button>

<script type="text/javascript" src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>
<script>
  // Só inicializa quando a API estiver disponível
  window['__onGCastApiAvailable'] = function(isAvailable) {
    if (!isAvailable) return;

    const context = cast.framework.CastContext.getInstance();
    context.setOptions({
      receiverApplicationId: chrome.cast.media.DEFAULT_MEDIA_RECEIVER_APP_ID,
      autoJoinPolicy: chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED
    });

    // Botão só funciona quando a API está pronta
    const castBtn = document.getElementById('castBtn');
    castBtn.addEventListener('click', () => {
      const session = context.getCurrentSession();

      function sendVideo() {
        const videoUrl = document.getElementById('localVideo').currentSrc;
        const mediaInfo = new chrome.cast.media.MediaInfo(videoUrl, 'video/mp4');
        const request = new chrome.cast.media.LoadRequest(mediaInfo);
        context.getCurrentSession().loadMedia(request).then(
          () => console.log('Vídeo enviado para TV!'),
          (err) => console.error('Erro ao transmitir:', err)
        );
      }

      if (!session) {
        context.requestSession();
        // Espera sessão iniciar
        context.addEventListener(cast.framework.CastContextEventType.SESSION_STATE_CHANGED, (event) => {
          if (event.sessionState === cast.framework.SessionState.SESSION_STARTED) {
            sendVideo();
          }
        });
      } else {
        sendVideo();
      }
    });
  };
</script>



</body>
</html>
