using Android.App;
using Android.OS;
using Android.Support.V7.App;
using Android.Runtime;
using Android.Widget;
using Android.Content;
using Android.Support.V4.Widget;
using Android.Support.Design.Widget;
using Android.Views;
using App1.Database;
using System.Collections.Generic;
using SQLite;
using App1.Model;
using Newtonsoft.Json;
using Java.IO;

using System;
using System.Threading.Tasks;
using Android.Support.Constraints;
using System.Text.RegularExpressions;
using Net.Daum.Android.Map;
using Android;
using Android.Content.PM;
using Android.Support.V4.Content;
using App1.Function.Adapter;
using static Android.Widget.AdapterView;
using System.Collections;
using System.Linq;

namespace App1
{
    [Activity(Label = "@string/app_name", MainLauncher = false)]
    public class MainActivity : AppCompatActivity
    {
      
        //initial settings.. including view, action bar
        private void Init()
        {
            SetContentView(Resource.Layout.activity_main);
            SupportActionBar.Hide();

     
        }

     

        void LoadFragment(int id)
        {
            Android.Support.V4.App.Fragment fragment = null;

            fragment = FragmentMy.NewInstance();

            if (fragment == null)
                return;

            SupportFragmentManager.BeginTransaction()
                .Replace(Resource.Id.content_frame, fragment)
                .Commit();
        }


        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            Xamarin.Essentials.Platform.Init(this, savedInstanceState);

            Init();
            CheckPermission();

            var bottomNavigation = FindViewById<BottomNavigationView>(Resource.Id.bottom_navigation);
            bottomNavigation.NavigationItemSelected += BottomNavigation_NavigationItemSelected;

        }

        private void BottomNavigation_NavigationItemSelected(object sender, BottomNavigationView.NavigationItemSelectedEventArgs e)
        {
            LoadFragment(e.Item.ItemId);
        }

      
        //ask user permission
        private void CheckPermission()
        {
            string[] permissions = new string[]
            {
                Manifest.Permission.AccessFineLocation,
                Manifest.Permission.ReadExternalStorage,
                Manifest.Permission.ReadPhoneNumbers,
                Manifest.Permission.ReadPhoneState,
                Manifest.Permission.ReadSms
            };

            Android.Support.V4.App.ActivityCompat.RequestPermissions(this, permissions, approvedPermissionCode);
            //위치, 파일, 알람 동의 얻기

            int i = 0;

            for (i = 0; i < permissions.Length; i++)
            {
                string tempP = permissions[i];
                if (ContextCompat.CheckSelfPermission(this, tempP) != (int)Permission.Granted)
                {
                    // We have permission, go ahead and use the camera.
                    if (tempP == Manifest.Permission.AccessFineLocation
                        && Android.Support.V4.App.ActivityCompat.ShouldShowRequestPermissionRationale(this, tempP))
                    {

                       
                    }
                    else
                    {
                        Android.Support.V4.App.ActivityCompat.RequestPermissions(this, new string[] { tempP }, approvedPermissionCode);
                    }

                }
            }
        }

        public override void OnRequestPermissionsResult(int requestCode, string[] permissions, [GeneratedEnum] Android.Content.PM.Permission[] grantResults)
        {
            Xamarin.Essentials.Platform.OnRequestPermissionsResult(requestCode, permissions, grantResults);

            if (requestCode == approvedPermissionCode)
            {
                // Received permission result for camera permission.
                System.Console.WriteLine("Received response for Location permission request.");

                // Check if the only required permission has been granted
                if ((grantResults.Length == 1) && (grantResults[0] == Permission.Granted))
                {
                    // Location permission has been granted, okay to retrieve the location of the device.
                    System.Console.WriteLine("Location permission has now been granted.");

                }
                else
                {
                    System.Console.WriteLine("Location permission was NOT granted.");
                }
            }
            else
            {
                base.OnRequestPermissionsResult(requestCode, permissions, grantResults);
            }

        }


    }
}