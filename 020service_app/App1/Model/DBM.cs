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
using Newtonsoft.Json;
using SQLite;

namespace App1.Model
{
    public class DBM//database management
    {
        [PrimaryKey, AutoIncrement, Column("_Id")]
        public int ID { get; set; }
        public bool PDB { get; set; }
        public DateTime PDBUpdate { get; set; }

        public bool ODB { get; set; }
        public DateTime ODBUpdate { get; set; }

        public bool MDB { get; set; }
        public DateTime MDBUpdate { get; set; }

        public DBM()
        {
        }
        public DBM(string option, bool available, DateTime updateDate)
        {
            switch (option)
            {
                case "product":
                    {
                        this.PDB = available;
                        this.PDBUpdate = updateDate;
                        break;
                    }
                case "order":
                    {
                        this.ODB = available;
                        this.ODBUpdate = updateDate;
                        break;
                    }
                case "map":
                    {
                        this.MDB = available;
                        this.MDBUpdate = updateDate;
                        break;
                    }
            }

        }
    }
}