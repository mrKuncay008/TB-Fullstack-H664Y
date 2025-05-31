import { Routes, Route, useLocation } from "react-router-dom";
import { Cog6ToothIcon } from "@heroicons/react/24/solid";
import { IconButton } from "@material-tailwind/react";
import {
  Sidenav,
  DashboardNavbar,
  Configurator,
} from "@/widgets/layout";
import routes from "@/routes";
import { useMaterialTailwindController, setOpenConfigurator } from "@/context";
import LoadingCard from "@/componens/loadingcard";
import { useEffect, useState } from "react";

export function Dashboard() {
  const [controller, dispatch] = useMaterialTailwindController();
  const { sidenavType } = controller;
  const [loading, setLoading] = useState(true);
  const location = useLocation();

  useEffect(() => {
      setLoading(true); 
      const timer = setTimeout(() => {
        setLoading(false); 
      }, 2000);
  
      return () => clearTimeout(timer);
    }, [location]);
  
  return (
    <div className="min-h-screen bg-blue-gray-50/50">
      <Sidenav
        routes={routes}
        brandImg={
          sidenavType === "dark" ? "/img/logo-ct.png" : "/img/logo-ct-dark.png"
        }
        />
    </div>
  )
}

export default Dashboard;
