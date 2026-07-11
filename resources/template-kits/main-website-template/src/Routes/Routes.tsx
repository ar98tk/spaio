import { createBrowserRouter } from "react-router";
import Home from "../Pages/Home";
import Main from "../Layout/Main";
import AboutPage from "../Pages/AboutPage";
import Layout2 from "../Layout/Layout2";
import Home2 from "../Pages/Home2";
import Layout3 from "../Layout/Layout3";
import Home3 from "../Pages/Home3";
import ServicePage from "../Pages/ServicePage";
import ServiceDetailsPage from "../Pages/ServiceDetailsPage";
import TeamPage from "../Pages/TeamPage";
import TeamDetailsPage from "../Pages/TeamDetailsPage";
import ProjectPage from "../Pages/ProjectPage";
import ProjectDetailsPage from "../Pages/ProjectDetailsPage";
import PricingPage from "../Pages/PricingPage";
import IntegrationsPage from "../Pages/IntegrationsPage";
import FaqPage from "../Pages/FaqPage";
import ContactPage from "../Pages/ContactPage";
import BlogStandardPage from "../Pages/BlogStandardPage";
import BlogDetailsPage from "../Pages/BlogDetailsPage";
import BlogPage from "../Pages/BlogPage";

export const router = createBrowserRouter([
    {
      path: "/",
      Component: Layout3,
      children: [                                     
        {
          path: "/about",
          Component: AboutPage
        },
        {
          path: "/service",
          Component: ServicePage
        },       
        {
          path: "/service/service-details",
          Component: ServiceDetailsPage
        },
        {
          path: "/team",
          Component: TeamPage
        },
        {
          path: "/team/team-details",
          Component: TeamDetailsPage
        },
        {
          path: "/project",
          Component: ProjectPage
        },
        {
          path: "/project/project-details",
          Component: ProjectDetailsPage
        },
        {
          path: "/pricing",
          Component: PricingPage
        },
        {
          path: "/integrations",
          Component: IntegrationsPage
        },
        {
          path: "/faq",
          Component: FaqPage
        },
        {
          path: "/contact",
          Component: ContactPage
        },        
      {
          path: "/blog-sidebar",
          Component: BlogStandardPage
        },  
      {
          path: "/blog/blog-details",
          Component: BlogDetailsPage
        },  
      {
          path: "/blog",
          Component: BlogPage
        }, 

      ],
    },
    {
      path: '/',
      Component: Layout2,
      children: [
        {
          index: true,
          Component: Home2,
        },                           
      ],
    },    
    {
      path: 'home2',
      Component: Main,
      children: [
        {
          index: true,
          Component: Home,
        },                           
      ],
    }, 
    {
      path: 'home3',
      Component: Layout3,
      children: [
        {
          index: true,
          Component: Home3,
        },                           
      ],
    },         
 
  ]);