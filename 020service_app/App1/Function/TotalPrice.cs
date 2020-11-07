namespace App1.Function
{
    public class TotalPrice
    {
        private int total=0;
        public void SetPrice(int type,int price)
        {
            total = type == 1 ? total + price : total - price;
        }

        public int GetTotal()
        {
            return total;
        }


    }

}