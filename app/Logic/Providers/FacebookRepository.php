<?php

namespace App\Logic\Providers;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Log;
use Facebook\Facebook;
use App\FacebookID;
use Illuminate\Support\Facades\Auth;


class FacebookRepository
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => config('facebook.config.app_id'),
            'app_secret' => config('facebook.config.app_secret'),
            'default_graph_version' => 'v12.0'
        ]);
    }

    public function redirectTo()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        $permissions = [
            'pages_manage_posts',
            'pages_read_engagement'
        ];

        $redirectUri = config('app.url') . '/auth/facebook/callback';
        //$redirectUri = 'https://f261-103-57-85-12.ngrok.io/auth/facebook/callback';

        return $helper->getLoginUrl($redirectUri, $permissions);
    }

    public function handleCallback()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        if (request('state')) {
            $helper->getPersistentDataHandler()->set('state', request('state'));
        }

        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            throw new Exception("Graph returned an error: {$e->getMessage()}");
        } catch(FacebookSDKException $e) {
            throw new Exception("Facebook SDK returned an error: {$e->getMessage()}");
        }

        if (!isset($accessToken)) {
            throw new Exception('Access token error');
        }

        if (!$accessToken->isLongLived()) {
            try {
                $oAuth2Client = $this->facebook->getOAuth2Client();
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                throw new Exception("Error getting a long-lived access token: {$e->getMessage()}");
            }
        }

        return $accessToken->getValue();
        
        //store acceess token in databese and use it to get pages
       
    }

    public function getPages($accessToken)
    {
        $pages = $this->facebook->get('/me/accounts', $accessToken);
        $pages = $pages->getGraphEdge()->asArray();

       
        return array_map(function ($item) {
            return [
                'provider' => 'facebook',
                'access_token' => $item['access_token'],
                'id' => $item['id'],
                'name' => $item['name'],
                'image' => "https://graph.facebook.com/{$item['id']}/picture?type=large"
            ];
        }, $pages);
    }

    public function getMe($accessToken){
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->facebook->get(
              '/me',
              $accessToken
            );
          } catch(\Exception $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Exception $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }

          
          $me = $response->getGraphUser()->asArray();
          
        return [
            'id' => $me['id'],
            'name' => $me['name'],
        ];
      
    }

    public function getGroups($accessToken){
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->facebook->get(
              '/me/groups',
              $accessToken
            );
          } catch(\Exception $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Exception $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          $groups = $response->getGraphEdge()->asArray();

          /* handle the result */

        return array_map(function ($item) {
            return [
                'provider' => 'facebook group',
                'id' => $item['id'],
                'name' => $item['name'],
                'privacy' => $item['privacy']
            ];
        }, $groups);


    }

    public function delPost($postIDtoDelete,$pageAccessToken){
        try {
            return $this->facebook->delete('/'.$postIDtoDelete ,array(), $pageAccessToken);
        } catch (\Exception $exception) {
            //notify user about error
            //throw new Exception("Error while posting : {$exception->getMessage()}", $exception->getCode());
            return false;
        }

    }

    public function post($accountId, $accessToken, $content, $images = [])
    {
        $data = ['message' => $content];

        $attachments = $this->uploadImages($accountId, $accessToken, $images);

        foreach ($attachments as $i => $attachment) {
            $data["attached_media[$i]"] = "{\"media_fbid\":\"$attachment\"}";
        }

        try {
            return $this->postData($accessToken, "$accountId/feed", $data);
        } catch (\Exception $exception) {
            //notify user about error
            //throw new Exception("Error while posting : {$exception->getMessage()}", $exception->getCode());
            return false;
        }
    }

    private function uploadImages($accountId, $accessToken, $images = [])
    {
        $attachments = [];

        foreach ($images as $image) {
            if (!file_exists($image)) continue;

            $data = [
                'source' => $this->facebook->fileToUpload($image),
            ];

            try {
                $response = $this->postData($accessToken, "$accountId/photos?published=false", $data);
                if ($response) $attachments[] = $response['id'];
            } catch (\Exception $exception) {
                throw new Exception("Error while posting : {$exception->getMessage()}", $exception->getCode());
            }
        }

        return $attachments;
    }

    private function postData($accessToken, $endpoint, $data)
    {
        try {
            $response = $this->facebook->post(
                $endpoint,
                $data,
                $accessToken
            );
            return $response->getGraphNode();

        } catch (FacebookResponseException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } catch (FacebookSDKException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }    
}