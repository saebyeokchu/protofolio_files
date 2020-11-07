using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.CompilerServices;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;

namespace App1.Function
{
    public class CustomAlertDialog
    {
        //콘텐츠 만들어서 back
        View content;
        public CustomAlertDialog(
            Context mContext,
            string titleText,
            string contentText,
            string cancelBtnText,
            string approveBtnText,
            ViewStates infoBtnVisibility)
        {
            content = LayoutInflater.From(mContext).Inflate(Resource.Layout.layout_custom_alert_dialog, null);

            content.FindViewById<TextView>(Resource.Id.alertDialogTitle).Text = titleText;
            content.FindViewById<TextView>(Resource.Id.alertDialogContent).Text = contentText;
            content.FindViewById<Button>(Resource.Id.alertDialogCancelBtn).Text = cancelBtnText;
            content.FindViewById<Button>(Resource.Id.alertDialogApproveBtn).Text = approveBtnText;

            content.FindViewById<ImageButton>(Resource.Id.alertDialogInfoBtn).Visibility = infoBtnVisibility;

        }

        //just notification, user interaction not required
        public CustomAlertDialog(Context mContext, string empasisText, string normalText,string closeBtnTitle)
        {
            content = LayoutInflater.From(mContext).Inflate(Resource.Layout.layout_custom_notify, null);

            content.FindViewById<TextView>(Resource.Id.noticeEmphasisText).Text = empasisText;
            content.FindViewById<TextView>(Resource.Id.noticeText).Text = normalText;
            content.FindViewById<TextView>(Resource.Id.closeNotify).Text = closeBtnTitle;
        }

        public View GetView()
        {
            return content;
        }
    }
}