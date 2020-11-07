using Android.Views;
using Android.Webkit;
using Android.Widget;

namespace App1.Function.Adapter
{
    class MyWebViewClient : WebViewClient
    { 
        // For API level 24 and later
        ProgressBar circularbar;

        public MyWebViewClient()
        {

        }
        public MyWebViewClient(ProgressBar circularbar)
        {
            this.circularbar = circularbar;
        }
        public override bool ShouldOverrideUrlLoading(WebView view, IWebResourceRequest request)
        {
            view.LoadUrl(request.Url.ToString());
            return false;
        }

        public override void OnPageFinished(WebView view, string url)
        {
            if(circularbar != null) circularbar.Visibility = ViewStates.Invisible;
            //base.OnPageFinished(view, url);
        }

    }
}