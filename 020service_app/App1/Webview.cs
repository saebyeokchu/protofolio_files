using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;
using Android.Webkit;
using Android.Support.V7.App;
using App1.Function.Adapter;

namespace App1
{
    [Activity(Label = "")]
    public class Webview : AppCompatActivity
    {
        WebView web_view;
        ProgressBar circularbar;

        CommonFunc commonFunc = new CommonFunc();

        string backTo;
        string actionBarTitle;

        private void SetCustomActoinBar()
        {
            SupportActionBar.SetDisplayShowCustomEnabled(true);
            SupportActionBar.SetDisplayHomeAsUpEnabled(false);
            SupportActionBar.SetDisplayShowTitleEnabled(false);
            SupportActionBar.SetDisplayShowHomeEnabled(false);


            View customActionBar = LayoutInflater.From(this).Inflate(Resource.Layout.layout_custom_actionbar, null);

            customActionBar.FindViewById<TextView>(Resource.Id.customBackBtn).Click += delegate
            {
                if (backTo == "mainActivity")
                {
                    commonFunc.MoveToActivity(this, typeof(MainActivity), new Dictionary<string, string>()
                        {
                            {"version","list"}
                        });
                }

                Finish();
            };

            customActionBar.FindViewById<TextView>(Resource.Id.customActionBarTitle).Text = actionBarTitle;

            var customInfoBtn = customActionBar.FindViewById<TextView>(Resource.Id.customInfoBtn);
            customInfoBtn.Visibility = ViewStates.Gone;

            SupportActionBar.SetCustomView(customActionBar,
                new Android.Support.V7.App.ActionBar.LayoutParams(WindowManagerLayoutParams.MatchParent, WindowManagerLayoutParams.WrapContent));
        }

        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            Xamarin.Essentials.Platform.Init(this, savedInstanceState);

            string url = Intent.Extras.GetString("url");
            backTo = Intent.Extras.GetString("backTo");
            actionBarTitle = Intent.Extras.GetString("actionBarTitle");

            SetCustomActoinBar();

            SetContentView(Resource.Layout.Webview);

            circularbar = FindViewById<ProgressBar>(Resource.Id.progressBar);
            circularbar.Visibility = ViewStates.Visible;
            circularbar.BringToFront();

            web_view = FindViewById<WebView>(Resource.Id.webview);
            web_view.Settings.JavaScriptEnabled = true;
            web_view.SetWebViewClient(new MyWebViewClient(circularbar));
            web_view.LoadUrl(url);
        }
    }
}