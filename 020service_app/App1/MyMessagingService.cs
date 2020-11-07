using System;
using System.Collections.Generic;
using System.Diagnostics.CodeAnalysis;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.Graphics;
using Android.OS;
using Android.Runtime;
using Android.Support.V4.App;
using Android.Views;
using Android.Widget;
using Firebase.Messaging;
using TaskStackBuilder = Android.App.TaskStackBuilder;

namespace App1
{
    [Service]
    [IntentFilter(new[] { "com.google.firebase.MESSAGING_EVENT" })]
    public class MyMessagingService : FirebaseMessagingService
    {
        private readonly string NOTIFICATION_CHANNEL_ID = "com.companyname.app1";

        public override void OnMessageReceived(RemoteMessage message)
        {
            //When we receive message from FCM
            //First , check custom data, if it is not empty, show this data
            //else, just show title and body
            if (!message.Data.GetEnumerator().MoveNext())
                SendNotification(message.GetNotification().Title, message.GetNotification().Body);
            else
                SendNotification(message.Data);

        }

        private void SendNotification(IDictionary<string, string> data)
        {
            string title, body;
            data.TryGetValue("title", out title);
            data.TryGetValue("body", out body);

            SendNotification(title, body);

        }

        private void SendNotification(string title, string body)
        {
            NotificationManager notificationManager = (NotificationManager)GetSystemService(Context.NotificationService);

            if (Build.VERSION.SdkInt >= Android.OS.BuildVersionCodes.O)
            {
                NotificationChannel notificationChannel = new NotificationChannel(NOTIFICATION_CHANNEL_ID, "Notification channel",
                    Android.App.NotificationImportance.Default);

                notificationChannel.Description = "EDMTDev Channel";
                notificationChannel.EnableLights(true);
                notificationChannel.LightColor = Color.Blue;
                notificationChannel.SetVibrationPattern(new long[] { });

                notificationManager.CreateNotificationChannel(notificationChannel);
            }

            NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this, NOTIFICATION_CHANNEL_ID);
            Intent resultIntent = new Intent(this, typeof(ReceiptActivity));
            TaskStackBuilder stackBuilder = TaskStackBuilder.Create(this);
            stackBuilder.AddParentStack(Java.Lang.Class.FromType(typeof(ReceiptActivity)));
            stackBuilder.AddNextIntent(resultIntent);
            PendingIntent resultPendingIntent = stackBuilder.GetPendingIntent(0, PendingIntentFlags.UpdateCurrent);



            notificationBuilder.SetAutoCancel(true)
                .SetDefaults(-1)
                .SetWhen(DateTimeOffset.UtcNow.ToUnixTimeMilliseconds())
                .SetContentTitle(title)
                .SetContentText(body)
                .SetSmallIcon(Resource.Drawable.icon)
                .SetContentInfo("info")
                .SetPriority(NotificationCompat.PriorityDefault)
                .SetContentIntent(resultPendingIntent)
                .SetAutoCancel(true);

            notificationManager.Notify(new Random().Next(), notificationBuilder.Build());

        }
    }
}