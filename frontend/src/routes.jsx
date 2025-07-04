import {
  HomeIcon,
  TableCellsIcon,
  ArrowsPointingOutIcon,
  ServerStackIcon,
  RectangleStackIcon,
  ArrowLeftOnRectangleIcon,
  BookOpenIcon,
  ChatBubbleLeftIcon,
} from "@heroicons/react/24/solid";
import { Home, Income } from "@/pages/dashboard";
import { SignIn, SignUp } from "@/pages/auth";
import Outcome from "./pages/dashboard/outcome";
import Pembukuan from "./pages/dashboard/pembukuan";
import Tanyaai from "./pages/dashboard/tanyaai";

const icon = {
  className: "w-5 h-5 text-inherit",
};

export const routes = [
  {
    layout: "dashboard",
    pages: [
      {
        icon: <HomeIcon {...icon} />,
        name: "dashboard",
        path: "/home",
        element: <Home />,
      },
      {
        icon: <TableCellsIcon {...icon} />,
        name: "Anggaran Masuk",
        path: "/income",
        element: <Income />,
      },
      {
        icon: <ArrowsPointingOutIcon {...icon} />,
        name: "Anggaran Keluar",
        path: "/outcome",
        element: <Outcome />,
      },
      {
        icon: <BookOpenIcon {...icon} />,
        name: "Pembukuan",
        path: "/pembukuan",
        element: <Pembukuan />,
      },
      {
        icon: <ChatBubbleLeftIcon {...icon} />,
        name: "Tanya Ai",
        path: "/tanya-ai",
        element: <Tanyaai />,
      },
    ],
  },
  {
    title: "auth pages",
    layout: "auth",
    pages: [
      {
        icon: <ServerStackIcon {...icon} />,
        name: "sign in",
        path: "/sign-in",
        element: <SignIn />,
      },

      {
        icon: <ArrowLeftOnRectangleIcon {...icon} />,
        name: "Logout",
        path: "/",
        element: <SignIn />,
      },
      {
        icon: <RectangleStackIcon {...icon} />,
        name: "sign up",
        path: "/sign-up",
        element: <SignUp />,
      },
    ],
  },
];

export default routes;
