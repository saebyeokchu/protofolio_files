using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Support.V7.App;
using Android.Views;
using Android.Webkit;
using Android.Widget;
using App1.Function;
using App1.Function.Adapter;

namespace App1
{
    [Activity()]
    class KakaoLogInActivity : AppCompatActivity
    {
        WebView web_view;

        public override bool OnOptionsItemSelected(IMenuItem item)
        {
            //Back button pressed -> toggle event
            if (item.ItemId == Android.Resource.Id.Home)
            {
                Finish();
                OverridePendingTransition(0, 0);
                return true;
            }

            return base.OnOptionsItemSelected(item);
        }

        //토큰 필요하면 나중에 추가하기

        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            Xamarin.Essentials.Platform.Init(this, savedInstanceState);
            SupportActionBar.SetDisplayHomeAsUpEnabled(true);

            SetContentView(Resource.Layout.Webview);

            WebviewBridge webviewBridge = new WebviewBridge(this);

            web_view = FindViewById<WebView>(Resource.Id.webview);
            web_view.SetWebChromeClient(new WebChromeClient());
            web_view.Settings.JavaScriptEnabled = true;
            web_view.SetWebViewClient(new MyWebViewClient());
            web_view.AddJavascriptInterface(webviewBridge, "app");

        }
    }
}