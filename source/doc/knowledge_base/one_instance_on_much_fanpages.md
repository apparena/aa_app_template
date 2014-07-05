# Use one app instance on much Facebook Fanpages
You can use one app instance on much Facebook pages. To do this, you must set the app URL inclusive the instance id, the locale and the extension */idbyfb/* to the *Page Tab URL* and *Secure Page Tab URL* of your Facebook app settings. With this settings we route the request to the idbyfb action method in our main controller to specify the i_id and the locale settings. After that, we redirect the page with the sign_request data to our index action.

## Example URL's
- Normal setting version: https://onlineapp.co/
- Defined i_id to use on much fanpages: https://onlineapp.co/1234/de_DE/idbyfb/
