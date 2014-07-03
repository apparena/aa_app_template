# Internal cache handler
To enlarge the app performance and reduce the API calles, that are very slow, we include a little caching handler. Currently we use only a simple file cache, but we plan to expand this to use memcache and apc-cache.

The file cache put all cached files to /tmp/cache/ and start all files with an defined prefix, followed with the app instance id and a md5 string thats generatet from the cache name.
**Example:** [PREFIX]_[INSTANCE-ID]_[MD5-STRING]

## Start caching
In this example, we will cache a new css file. To do that, we initialize the cache helper and put as parameter the prefix name (in our example, we use css):

```php
$cache = \Apparena\Helper\Cache::init('css');
```

Now we check, if a cached file exist. For this, we need the filename as md5 string:

```php
$cachename = md5('style');
if ($cache->check($cachename))
{
	// cache exist, get data from them
	$cachedata = $cache->get($cachename);
	return $cachedata[0];
}
```

If a cached file exist, we get back an array and we returned the first and only array key. Otherwise we cache the css code:

```php
if ($cache->check($cachename))
{
	[...]
}
else
{
	[...]
	Create the css data and store them in $css
	[...]
	// cache $css value with name from $cachename
	$cache->add($cachename, array($css));

	// additional return the $css value. Next time we used the cached one
	return $css;
}
```

The cached value will be stored for instance id 1234 in the file: **css_1234_a1b01e734b573fca08eb1a65e6df9a38**

## Clear cache
There are to different ways to clear the app cache:

### Only for one instance
To clear the hole cache only for one instance, call the URL http://yourappurl.com/[INSTANCE-ID]/cache
That will erase all cached files for defined instance id.

### Clear hole app cache
If you want to clear the hole app cache, that means for all instance id's call this URL:
http://yourappurl.com/cache