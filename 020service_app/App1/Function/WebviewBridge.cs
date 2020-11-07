using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.Graphics;
using Android.OS;
using Android.Runtime;
using Android.Support.V7.App;
using Android.Telephony;
using Android.Views;
using Android.Webkit;
using Android.Widget;
using App1.Database;
using App1.Model;
using Java.Interop;
using static Android.Telecom.Call;

namespace App1.Function
{
    class WebviewBridge : Java.Lang.Object
    { 

        public string GetAddress()
        {
            return address;
        }

        public bool GetKakaoLoginResult()
        {
            return kakaoLoginResult;
        }

        [Export("SetAddress")]
        [JavascriptInterface]
        public void SetAddress(string address)
        {
            this.address = address;

            commonFunc.hideAddress(webView, confirmAddressText, resetAddressBtn);

            confirmAddressText.Text = address;
            addressDoneBtn.Text = "주소 설정하기";
            

        }

    }
}