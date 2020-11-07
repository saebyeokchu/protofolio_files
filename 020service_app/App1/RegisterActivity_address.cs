using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.Graphics;
using Android.Nfc;
using Android.OS;
using Android.Support.V7.App;
using Android.Util;
using Android.Views;
using Android.Webkit;
using Android.Widget;
using App1.Database;
using App1.Function;
using App1.Function.Adapter;

namespace App1
{
    [Activity(Label = "주소 설정")]
    public class RegisterActivity_address : AppCompatActivity
    {
        
        private void SetCustomActoinBar()
        {
            SupportActionBar.SetDisplayShowCustomEnabled(true);
            SupportActionBar.SetDisplayHomeAsUpEnabled(false);
            SupportActionBar.SetDisplayShowTitleEnabled(false);
            SupportActionBar.SetDisplayShowHomeEnabled(false);


            View customActionBar = LayoutInflater.From(this).Inflate(Resource.Layout.layout_custom_actionbar, null);

            customActionBar.FindViewById<TextView>(Resource.Id.customBackBtn).Click += delegate
            {
                Finish();
            };

            customActionBar.FindViewById<TextView>(Resource.Id.customActionBarTitle).Text = "회원가입";

            customActionBar.FindViewById<TextView>(Resource.Id.customInfoBtn).Visibility = ViewStates.Gone;

            SupportActionBar.SetCustomView(customActionBar, new Android.Support.V7.App.ActionBar.LayoutParams(WindowManagerLayoutParams.MatchParent, WindowManagerLayoutParams.WrapContent));
        }

        private void Init()
        {
            SetCustomActoinBar();

        }

        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);

            DrawAddressWebView();

            Init();
            SetAddress();
        }

        private void SetAddress()
        {

            loginAddressBtn.Text = "다음에 설정하기";

            loginAddressBtn.Click += async delegate
            {
                if(registerResult=="success")
                {

                }
                else
                {
                }
                

            };
        }

        private void DrawAddressWebView()
        {
            SetContentView(Resource.Layout.login_address);

            loginAddressBtn = FindViewById<Button>(Resource.Id.loginAddressBtn);
            webView = FindViewById<WebView>(Resource.Id.webViewAddress);
            confirmAddressText = FindViewById<TextView>(Resource.Id.confirmAddressText);
            resetAddressBtn = FindViewById<TextView>(Resource.Id.resetAddressBtn);
            progressBar = FindViewById<ProgressBar>(Resource.Id.progressBar);

            webviewBridge = new WebviewBridge(this, loginAddressBtn, webView, confirmAddressText, resetAddressBtn);

            webView.SetInitialScale(200);
            webView.Settings.SetSupportZoom(false);
            webView.Settings.JavaScriptEnabled = true;
            webView.Settings.JavaScriptCanOpenWindowsAutomatically = true;
            webView.Settings.AllowFileAccess = true;
            webView.Settings.SetSupportMultipleWindows(true);
            webView.Settings.DomStorageEnabled = true;

            webView.SetWebChromeClient(new WebChromeClient());
            webView.SetWebViewClient(new MyWebViewClient());
            webView.AddJavascriptInterface(webviewBridge, "app");

            resetAddressBtn.Click += delegate
            {
                webView.Visibility = ViewStates.Visible;
                confirmAddressText.Visibility = ViewStates.Invisible;
                resetAddressBtn.Visibility = ViewStates.Invisible;
            };
        }
    }
}