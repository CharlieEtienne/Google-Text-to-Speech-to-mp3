<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Google Text to Speech to mp3</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/fbb35493dc.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #F9FAFB;
        }
        #t2s {
            width: 100%;
            max-width: 600px;
            padding: 15px;
            margin: auto;
        }
        #equalizer {
            position: relative;
            background-image: url(ani_equalizer_white.gif);
            background-size: cover;
            background-position: center;
            width: 26px;
            height: 16px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <form id="t2s" method="POST" action="process.php">
            
            <h1 class="h3 mb-5 mt-5 font-weight-normal text-center">Google Text To Speech 2 mp3</h1>
    
            <div class="form-group">
                <label for="voice-name">Type de voix</label>
                <select class="form-control" name="voice-name" id="voice-name">
                    <option value="fr-FR-Wavenet-A">fr-FR-Wavenet-A (Femme)</option>
                    <option value="fr-FR-Wavenet-B">fr-FR-Wavenet-B (Homme)</option>
                    <option value="fr-FR-Wavenet-C">fr-FR-Wavenet-C (Femme)</option>
                    <option value="fr-FR-Wavenet-D">fr-FR-Wavenet-D (Homme)</option>
                    <option value="fr-FR-Wavenet-E">fr-FR-Wavenet-E (Femme)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="text">Texte</label>
                <textarea  class="form-control" rows="10" type="text" name="text" id="text"></textarea>
            </div>
            
            <div class="form-group text-center">
                <button id="play" type="submit" class="btn btn-info rounded-0 btn-lg mr-4"><i class="far fa-play-circle"></i> Écouter 
                    <span id="equalizer"></span>
                </button>
                <a id="download" class="btn btn-info rounded-0 btn-lg" href="output.mp3" target="_blank"><i class="far fa-arrow-alt-circle-down"></i> Télécharger</a>
            </div>
        </form>
        <p class="mt-3 mb-3 text-muted text-center">Made with <i class="far fa-heart text-danger"></i> by <a href="https://github.com/CharlieEtienne" target="_blank">@CharlieEtienne</a></p>
        
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script>
        var audio       = new Audio();
        var equalizer   = $('#equalizer');
        var form        = $('#t2s');
        var url         = form.attr('action');
        var play_btn    = $('#play');
        var play_icon   = $('#play>i');

        audio.onplaying = function() { equalizer.css('display','inline-block'); };
        audio.onended = function() { 
            this.currentTime = 0; 
            equalizer.css('display','none');
            play_btn.removeClass('playing');
            play_icon.removeClass('fa-pause-circle').addClass('fa-play-circle'); 
        };
        audio.onpause = function() { 
            this.currentTime = 0; 
            equalizer.css('display','none');
            play_btn.removeClass('playing');
            play_icon.removeClass('fa-pause-circle').addClass('fa-play-circle');
        };
        
        $('#t2s').on('submit', function(e){
            e.preventDefault();
            $.ajax({ 
                method: "POST",
                url: url, 
                data: form.serialize(),
                dataType: 'json',
                success: function(response){
                    if(play_btn.hasClass('playing')){
                        audio.pause();
                        audio.currentTime = 0; 
                        equalizer.css('display','none');
                        play_btn.removeClass('playing');
                        play_icon.removeClass('fa-pause-circle').addClass('fa-play-circle');
                    }
                    else {
                        var url = "/output.mp3?cb=" + new Date().getTime();
                        audio.src = url;
                        audio.load();
                        audio.play();
                        play_btn.addClass('playing');
                        play_icon.removeClass('fa-play-circle').addClass('fa-pause-circle');
                    }
                },
                error: function(response){
                    console.log(response);
                }
            });
        });
        $(document).on('click', '#download', function (e) {
            e.preventDefault();
            $.ajax({ 
                method: "POST",
                url: url, 
                data: form.serialize(),
                dataType: 'json',
                success: function(response){
                    audio.pause();
                    window.open("/output.mp3", '_blank');
                },
                error: function(response){
                    console.log(response);
                }
            });
        });
    </script>
</body>
</html>
