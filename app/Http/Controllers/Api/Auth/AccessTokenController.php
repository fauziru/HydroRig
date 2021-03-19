<?php
namespace App\Http\Controllers\Api\Auth;

use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Response;
use \Laravel\Passport\Http\Controllers\AccessTokenController as ATC;
use App\Http\Resources\User as UserResource;

class AccessTokenController extends ATC
{
    public function issueToken(ServerRequestInterface $request)
    {
        try {
            $grant_type = $request->getParsedBody()['grant_type'];
            $user = [];
            if ($grant_type == 'password') {
              //get username (default is :email)
              $email = $request->getParsedBody()['email'];
              //get user
              //change to 'email' if you want
              $user = new UserResource(User::where('email', '=', $email)->first());
              $user = collect($user);
            }

            //generate token
            $tokenResponse = parent::issueToken($request);

            //convert response to json string
            $content = $tokenResponse->getContent();

            //convert json to array
            $data = json_decode($content, true);

            if(isset($data["error"]))
                throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);

            //add access token to user
            $payload = collect([]);
            $payload->put('token_type', $data['token_type']);
            $payload->put('access_token', $data['access_token']);
            $payload->put('expires_in', $data['expires_in']);
            $payload->put('refresh_token', $data['refresh_token']);

            return Response::json(array('user' => $user, 'payload' => $payload));
        }
        catch (ModelNotFoundException $e) { // email notfound
            //return error message
            return response(["message" => "User not found"], 500);
        }
        catch (OAuthServerException $e) { //password not correct..token not granted
            //return error message
            return response(["message" => "The user credentials were incorrect.', 6, 'invalid_credentials"], 500);
        }
        catch (Exception $e) {
            ////return error message
            return response(["message" => $e->getMessage()], 500);
        }
    }
}
