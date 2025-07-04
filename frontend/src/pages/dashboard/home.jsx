
import React, { useEffect, useState } from "react";
import { StatisticsCard } from "@/widgets/cards";
import {
  BanknotesIcon,
  ChartBarIcon,
  CurrencyDollarIcon,
} from "@heroicons/react/24/solid";

import { Typography } from "@material-tailwind/react";
import { apiFetchDashboard } from "../../api/dashboardapi.js";
import ChartDashboard2024 from "../../widgets/charts/chart-dashboard2024.jsx"
import { apiFetchUser } from "@/api/getuser.js";
import ChartDashboard2025 from "@/widgets/charts/chart-dashboard2025.jsx";

export function Home() {
    const [dataDashboard, setDataDashboard] = useState([]);
    
    useEffect(() => {
      const fetchData = async () => {
        try {
          const fetchedDataDashboard = await apiFetchDashboard();
          setDataDashboard([
          {
            color: "white",
            icon: BanknotesIcon,
            title: "Anggaran Pengeluaran Belanja",
            colorBody: "red",
            value: new Intl.NumberFormat('id-ID', {
              style: 'currency',
              currency: 'IDR',
              minimumFractionDigits: 0,
            }).format(fetchedDataDashboard.data.totalOutcome),

            footer: {
              color: "white",
              value: "-",
              label: "Pengeluaran",
            },
          },
          {
            color: "white",
            icon: CurrencyDollarIcon,
            title: "Laporan Kas Masuk",
            colorBody: "light-blue",
            value: new Intl.NumberFormat('id-ID', {
              style: 'currency',
              currency: 'IDR',
              minimumFractionDigits: 0,
            }).format(fetchedDataDashboard.data.totalIncome),

            footer: {
              color: "white",
              value: "-",
              label: "Pemasukan",
            },
          },
          {
            color: "gray",
            icon: ChartBarIcon,
            title: "Sisa Saldo Anggaran",
            colorBody: "yellow",

            value: new Intl.NumberFormat('id-ID', {
              style: 'currency',
              currency: 'IDR',
              minimumFractionDigits: 0,
            }).format(fetchedDataDashboard.data.balance),
            footer: {
              color: fetchedDataDashboard.data.balance >= 0 ?"text-green-500" : "text-red-500",
              value: `${(fetchedDataDashboard.data.balance / fetchedDataDashboard.data.totalIncome * 100).toFixed(2)}%`,
              label: "Sisa Dana",
            },
          },
        ]);
        } catch (error) {
          console.error("Error fetching data:", error); 
        }
      };
  
      fetchData(); 
    }, []);

    const [user, setUser] = useState();
    const handleUser = async () => {
      try {
        const data = await apiFetchUser();
        setUser(data);

      } catch (error) {
        console.error('Error fetching income data:', error);
      }
    };

    useEffect(() => {
      handleUser();
    }, []);

  return (
    <>
    <div>
      {user ? (

        <h1 className="mb-10 font-normal text-gray-800">Hallo <b>{user.name}</b></h1>
      ) : (
        <h1 className="mb-3"> NaN</h1>
      )}
   <div className="max-w-screen-xl mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
  {dataDashboard.map(({ icon, title, footer, ...rest }) => (
    <StatisticsCard
      key={title}
      {...rest}
      title={title}
      icon={React.createElement(icon, {
        className: "w-6 h-6",
      })}
      footer={
        <Typography className="font-semibold">
          <strong className={footer.color}>{footer.value}</strong>
          &nbsp;{footer.label}
        </Typography>
      }
    />
  ))}
</div>

    </div>
    <div>
      <ChartDashboard2025 />
    </div>
      <ChartDashboard2024/>
    </>
  );
}

export default Home;
