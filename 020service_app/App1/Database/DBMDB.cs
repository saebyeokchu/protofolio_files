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
using Android.OS;
using Android.Runtime;
using Android.Util;
using Android.Views;
using Android.Widget;
using App1.Model;
using Newtonsoft.Json;
using SQLite;

namespace App1.Database
{
    class DBMDB
    {
        readonly String folder = System.Environment.GetFolderPath(System.Environment.SpecialFolder.Personal);
        readonly CommonFunc commonFunc = new CommonFunc();

        private static string TAG = "*************** Database Management DB **************** \n";

        private void PrintDataAll()
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));
            try
            {
                using (connection)
                {
                    List<DBM> tempList = connection.Table<DBM>().ToList();
                    foreach (var data in tempList)
                    {
                        Console.WriteLine("Product table exist ? " + data.PDB);
                        Console.WriteLine("Product table created date : " + data.PDBUpdate);
                        Console.WriteLine("Order table exist ? " + data.ODB);
                        Console.WriteLine("Order table created date : " + data.ODBUpdate);
                        Console.WriteLine("Map table exist ? " + data.MDB);
                        Console.WriteLine("Map table created date : " + data.MDBUpdate);
                    }
                }

            }
            catch (SQLiteException ex)
            {
                Log.Debug(TAG, ex.Message);

            }
        }


        public DBM[] GetDBMArray()
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));
            try
            {
                using (connection)
                {
                    return connection.Table<DBM>().ToArray();
                }

            }
            catch (SQLiteException ex)
            {
                Log.Debug(TAG, ex.Message);
                return null;

            }
        }

        public void DropTable()
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));
            try
            {
                using (connection)
                {
                    connection.DropTable<DBM>();
                }

            }
            catch (SQLiteException ex)
            {
                Log.Debug(TAG, ex.Message);

            }
        }

        public void CreateTable()
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));
            try
            {
                using (connection)
                {
                    connection.CreateTable<DBM>();
                }

            }
            catch (SQLiteException ex)
            {
                Log.Debug(TAG, ex.Message);

            }
        }

        public void Insert(DBM temp)
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));

            try
            {
                using (connection)
                {
                    connection.Insert(temp);
                }

            }
            catch (SQLiteException ex)
            {
                Console.WriteLine("APP1 ERROR : database/loginstate.cs/dropTable has error => " + ex.Message);

            }
        }

        public void Update(string option, bool available, DateTime updateDate)
        {
            var connection = new SQLiteConnection(System.IO.Path.Combine(folder, "Persons.db"));
            DBM tempDBM = GetDBMArray()[0];

            switch (option)
            {
                case "product":
                    {
                        tempDBM.PDB = available;
                        tempDBM.PDBUpdate = updateDate;
                        break;
                    }
                case "order":
                    {
                        tempDBM.ODB = available;
                        tempDBM.ODBUpdate = updateDate;
                        break;
                    }
                case "map":
                    {
                        tempDBM.MDB = available;
                        tempDBM.MDBUpdate = updateDate;
                        break;
                    }
            }

            try
            {
                using (connection)
                {
                    connection.Update(tempDBM);
                }

            }
            catch (SQLiteException ex)
            {
                Console.WriteLine("APP1 ERROR : database/loginstate.cs/dropTable has error => " + ex.Message);

            }

            PrintDataAll();
        }


        public void LogOut(OrderInfoDB oidb,UserInfoDB uidb,MapInfoDB midb,ProductDB pdb)
        {
            ResetDBEnvironment(new LoginState(false, ""));

            //drop all tables
            oidb.GetOrderInfo("dropT");
            oidb.GetOrderInfo("createT");
            uidb.DropTable();
            uidb.CreateTable();
            midb.GetMapInfo("dropT");
            pdb.GetProduct("dropT");
        }

        public async Task<int> LogIn(Context mContext, string phone, string pw, UserInfoDB uidb,bool isKakao,bool autoLogin)
        {
            int result = 0;
            HttpClient client = new HttpClient();
            client.BaseAddress = new Uri(tempURL);
            client.DefaultRequestHeaders.Accept.Clear();
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));


            var values = new Dictionary<string, string>
                {
                    {"USER_ID", phone},
                    {"PASSWORD", pw},
                    {"isKakao", isKakao.ToString()}
                };

            var encodedContent = new FormUrlEncodedContent(values);
            var requestAll = client.PostAsync("get/get_user_login.php", encodedContent);

            HttpResponseMessage requestAllMessage = await requestAll;

            if (requestAllMessage.IsSuccessStatusCode)
            {
                string resultJson = await requestAllMessage.Content.ReadAsStringAsync();
                Console.WriteLine("Login database reuslt " + resultJson);

                switch (resultJson.Trim())
                {
                    case "-2":
                        {
                            //commonFunc.giveToast(mContext, "존재하지 않는 아이디 입니다");
                            result = -2;
                            break;
                        }
                    case "11":
                        {
                            //commonFunc.giveToast(mContext, "비밀번호가 일치하지 않습니다");
                            result = 11;
                            break;
                        }
                    default:
                        {
                            UserInfo data = JsonConvert.DeserializeObject<UserInfo>(resultJson);
                            SetLoginAlterData(uidb, data, autoLogin);
                            break;
                        }
                }


            }

            return result;


        }

        public void SetLoginAlterData(UserInfoDB uidb, UserInfo data,bool autoLogin)
        {
            uidb.ResetDBEnvironment(data);
            ResetDBEnvironment(new LoginState(autoLogin, DateTime.Now.ToString()));
        }

    }

}