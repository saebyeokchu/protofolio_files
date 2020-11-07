using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Support.Constraints;
using Android.Views;
using Android.Widget;

namespace App1.Function.Adapter
{
    class BottomSheetListViewAdapter : BaseAdapter
    {
        string[] cancelReasons;
        ArrayList returnStringResults = new ArrayList();
        Context mContext;

        public BottomSheetListViewAdapter(string[] cancelReasons, Context mContext)
        {
            this.cancelReasons = cancelReasons;
            this.mContext = mContext;
        }
        public override int Count => cancelReasons.Length;

        public override Java.Lang.Object GetItem(int position)
        {
            throw new NotImplementedException();
        }

        public override long GetItemId(int position)
        {
            throw new NotImplementedException();
        }

        public override View GetView(int position, View convertView, ViewGroup parent)
        {
            View customView = null;

            if (convertView == null)
            {
                customView = LayoutInflater.From(mContext).Inflate(Resource.Layout.layout_bottomsheet_listview, null);
            }

            RadioButton radioButton = customView.FindViewById<RadioButton>(Resource.Id.radioButton);

            radioButton.Text = cancelReasons[position];

            radioButton.Click += delegate
            {
                adjustClickResult(1, radioButton.Text);
            };

            return customView;


        }

        private void adjustClickResult(int option,string clickedText)
        {
            if (option == 1) returnStringResults.Add(clickedText);
            else returnStringResults.RemoveAt(returnStringResults.IndexOf(clickedText)-1);

        }

        public ArrayList GetClickedResult() { return returnStringResults; }
    }
}