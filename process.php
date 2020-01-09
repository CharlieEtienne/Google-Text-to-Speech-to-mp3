<?php
ob_start();

require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

putenv("GOOGLE_APPLICATION_CREDENTIALS=credentials.json");

function synthesize_text($text)
{
    // create client object
    $client = new TextToSpeechClient();

    $input_text = (new SynthesisInput())
        ->setText($text);

    // note: the voice can also be specified by name
    // names of voices can be retrieved with $client->listVoices()

    $voice_gender = $_POST['voice-gender'] ?? '1';
    $voice_name = $_POST['voice-name'] ?? 'fr-FR-Wavenet-D';

    $voice = (new VoiceSelectionParams())
        ->setLanguageCode('fr-FR')
        ->setName($voice_name)
        ->setSsmlGender($voice_gender);

    $audioConfig = (new AudioConfig())
        ->setAudioEncoding(AudioEncoding::LINEAR16);

    $response = $client->synthesizeSpeech($input_text, $voice, $audioConfig);
    $audioContent = $response->getAudioContent();

    file_put_contents('output.mp3', $audioContent);

    $client->close();
}

if(isset($_POST['text'])){
    synthesize_text($_POST['text']);
    echo json_encode(['success' => 'ok']);
}
else {
    echo json_encode(['error' => 'text missing']);
}
