<?php

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use SendGrid\Mail\Mail;

// Register the function with Functions Framework.
// This enables omitting the `FUNCTIONS_SIGNATURE_TYPE=http` environment
// variable when deploying. The `FUNCTION_TARGET` environment variable should
// match the first parameter.
FunctionsFramework::http('send', 'send');

function send(ServerRequestInterface $request): Response
{
  try {
    $post_params = $request->getParsedBody();
    $get_params = $request->getQueryParams();
    $params = array_merge($post_params, $get_params);
    $to = $params['to'] ?? null;
    if (empty($to)) {
      throw new \Exception('必須パラメータがありません。');
    }

    $subject = 'SendGrid送信テスト';
    $message = 'テスト本文';

    $email = new Mail();
    $email->setFrom(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
    $email->addTo($to);
    $email->setSubject($subject);
    $email->addContent('text/html', $message);
    $sendgrid = new \SendGrid(getenv('SENDGRID_KEY'));
    $response = $sendgrid->send($email);
    var_dump($response->statusCode() . ": " . ($response->body() ?? 'ok'));
    return new Response(200, [], 'ok');
  } catch (\Throwable $th) {
    error_log($th->getMessage());
    return new Response(400, [], 'ng');
  }
}
