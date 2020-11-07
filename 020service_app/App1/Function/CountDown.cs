using Android.App;
using Android.OS;
using Android.Views;
using Android.Widget;
using App1;
using Java.Security.Cert;
using System;

class CountDown : CountDownTimer
{
    TextView view;
    Button resendBtn;
    TextView warning;
   
    CommonFunc commonFunc = new CommonFunc();

    public CountDown(TextView view, long totalTime, long interval) : base(totalTime, interval)
    {
        this.view = view;
    }

    public CountDown(TextView warning,Button resendBtn, TextView view, long totalTime, long interval) : base(totalTime, interval)
    {
        this.view = view;
        this.resendBtn = resendBtn;
        this.warning = warning;
    }

    public override void OnFinish()
    {
        if (resendBtn != null)
        {
            resendBtn.Visibility = ViewStates.Visible;
            warning.Text = "세션이 만료되었습니다";
        }
    }

    public override void OnTick(long millisUntilFinished)
    {
        int days = (int)(millisUntilFinished / (1000 * 60 * 60 * 24));
        int hours = (int)(millisUntilFinished / (1000 * 60 * 60) % 24);
        int mins = (int)(millisUntilFinished / (1000 * 60) % 60);
        int secs = (int)(millisUntilFinished / (1000) % 60);

        if (millisUntilFinished <= 3600000) view.Text = string.Format("{0:00}:{1:00}", mins, secs);
        else view.Text = string.Format("{1:00}:{2:00}:{3:00}", days, hours, mins, secs);
    }

}
