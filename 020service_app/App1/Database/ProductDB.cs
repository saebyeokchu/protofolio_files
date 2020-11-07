using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Runtime.CompilerServices;
using System.Text;
using System.Threading.Tasks;
using Android.App;
using Android.Content;
using Android.Nfc;
using Android.OS;
using Android.Runtime;
using Android.Util;
using Android.Views;
using Android.Widget;
using App1.Model;
using Java.Util;
using Newtonsoft.Json;
using SQLite;

namespace App1.Database
{
    class ProductDB
    {
        String folder = System.Environment.GetFolderPath(System.Environment.SpecialFolder.Personal);
        private string TAG = "APP1 ERROR : database/userinfo.cs/";

        CommonFunc commonFunc = new CommonFunc();

        private void PrintDataAll(SQLiteConnection connection)
        {
            try
            {
                using (connection)
                {
                    List<Product> tempList = connection.Table<Product>().ToList();
                    Console.WriteLine("==============================");
                    Console.WriteLine("name\tprice");
                    foreach (var data in tempList)
                    {
                        Console.WriteLine(data.productName+"\t"+data.productPrice);
                    }
                    Console.WriteLine("==============================");
                }

            }
            catch (SQLiteException ex)
            {
                Console.WriteLine("APP1 ERROR : database/userinfo.cs/getuserid has error => " + ex.Message);
                //return null;

            }
        }

        public Product[] GetProductArray()
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));

            /*List<Product> tempList = connection.Table<Product>().ToList();
            foreach (var data in tempList)
            {
                Console.WriteLine(data.productName + "\t" + data.productPrice + "\t" + data.categoryName);
            }*/

            try
            {
                using (connection)
                {
                    return connection.Table<Product>().ToArray();
                }

            }
            catch (SQLiteException ex)
            {
                Console.WriteLine("APP1 ERROR : database/orderstate.cs/GetIsOrderWaiting has error => " + ex.Message);
                return null;

            }
        }

        private void DropTable(SQLiteConnection connection)
        {
            try
            {
                using (connection)
                {
                    connection.DropTable<Product>();
                }

            }
            catch (SQLiteException ex)
            {
                Log.Debug(TAG, "dropTable has error : " + ex.Message);

            }
        }

        private void CreateTable(SQLiteConnection connection)
        {
            try
            {
                using (connection)
                {
                    connection.CreateTable<Product>();
                }

            }
            catch (SQLiteException ex)
            {
                Log.Debug(TAG, "Create table has error : " + ex.Message);
            }
        }

        public void Insert(Product p)
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));
            Product data = new Product(p.agencyID,p.categoryCode,p.productCode,p.productName,p.productPrice,p.categoryName);

            try
            {
                using (connection)
                {
                    connection.Insert(data);
                }

            }
            catch (SQLiteException ex)
            {
                Log.Debug(TAG, "Insert has error : " + ex.Message);

            }
        }

        public void ResetDBEnvironment(Product p)
        {
            GetProduct("dropT");
            GetProduct("createT");
            Insert(p);

            GetProduct("all"); //print all data in database

        }

        public async Task<bool> SetProductDB(int agencyID)
        {
            bool httpResult = false;

            Log.Debug(TAG, "try connection");
            HttpClient client = new HttpClient();
            client.BaseAddress = new Uri(tempURL);
            client.DefaultRequestHeaders.Accept.Clear();
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));

            var encodedContent = new FormUrlEncodedContent(values);

            HttpResponseMessage requestAllMessage = await requestAll;

            if (requestAllMessage.IsSuccessStatusCode)
            {
                string resultJson = await requestAllMessage.Content.ReadAsStringAsync();

                if (resultJson != "-2")
                {
                    Product[] product = JsonConvert.DeserializeObject<Product[]>(resultJson);

                    GetProduct("dropT");
                    GetProduct("createT");

                    foreach(Product p in product)
                    {
                        Insert(p);
                    }
                }
                else
                {
                    httpResult = true;
                }


            }
            else
            {
                Log.Debug(TAG, "connection failed");
            }

            return httpResult;


        }

    }

}