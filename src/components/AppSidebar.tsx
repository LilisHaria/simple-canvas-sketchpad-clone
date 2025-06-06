
import { Calendar, Home, History, Search, User, MapPin, Users, FileText, BarChart3 } from "lucide-react"
import { Link, useLocation } from "react-router-dom"

import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarHeader,
  SidebarFooter,
} from "@/components/ui/sidebar"

// Menu items for regular users
const userMainItems = [
  {
    title: "Dashboard",
    url: "/",
    icon: Home,
  },
  {
    title: "Booking",
    url: "/booking",
    icon: Calendar,
  },
  {
    title: "Cari Arena",
    url: "/search",
    icon: Search,
  },
]

const arenaItems = [
  {
    title: "Arena Indoor",
    url: "/booking/indoor",
    icon: MapPin,
  },
  {
    title: "Arena Outdoor", 
    url: "/booking/outdoor",
    icon: MapPin,
  },
  {
    title: "Arena Synthetic",
    url: "/booking/synthetic", 
    icon: MapPin,
  },
]

// Menu items for admin users
const adminMainItems = [
  {
    title: "Dashboard",
    url: "/admin/dashboard",
    icon: Home,
  },
  {
    title: "Kelola Lapangan",
    url: "/admin/arenas",
    icon: MapPin,
  },
  {
    title: "Kelola Booking",
    url: "/admin/bookings",
    icon: Calendar,
  },
  {
    title: "Kelola User",
    url: "/admin/users",
    icon: Users,
  },
  {
    title: "Laporan",
    url: "/admin/reports",
    icon: BarChart3,
  },
]

export function AppSidebar() {
  const location = useLocation()
  
  // Simple role detection - in real app this would come from auth context
  const isAdmin = location.pathname.startsWith('/admin')

  return (
    <Sidebar>
      <SidebarHeader className="border-b border-sidebar-border">
        <div className="flex items-center space-x-2 px-4 py-2">
          <span className="text-2xl">⚽</span>
          <span className="text-lg font-bold text-sidebar-foreground">ArenaKuy!</span>
        </div>
      </SidebarHeader>
      
      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel>
            {isAdmin ? "Menu Admin" : "Menu Utama"}
          </SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {(isAdmin ? adminMainItems : userMainItems).map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton asChild isActive={location.pathname === item.url}>
                    <Link to={item.url}>
                      <item.icon />
                      <span>{item.title}</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>

        {!isAdmin && (
          <SidebarGroup>
            <SidebarGroupLabel>Pilihan Arena</SidebarGroupLabel>
            <SidebarGroupContent>
              <SidebarMenu>
                {arenaItems.map((item) => (
                  <SidebarMenuItem key={item.title}>
                    <SidebarMenuButton asChild isActive={location.pathname === item.url}>
                      <Link to={item.url}>
                        <item.icon />
                        <span>{item.title}</span>
                      </Link>
                    </SidebarMenuButton>
                  </SidebarMenuItem>
                ))}
              </SidebarMenu>
            </SidebarGroupContent>
          </SidebarGroup>
        )}

        {isAdmin && (
          <SidebarGroup>
            <SidebarGroupLabel>Lainnya</SidebarGroupLabel>
            <SidebarGroupContent>
              <SidebarMenu>
                <SidebarMenuItem>
                  <SidebarMenuButton asChild isActive={location.pathname === "/history"}>
                    <Link to="/history">
                      <History />
                      <span>Booking Saya</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              </SidebarMenu>
            </SidebarGroupContent>
          </SidebarGroup>
        )}
      </SidebarContent>

      <SidebarFooter className="border-t border-sidebar-border">
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton>
              <User />
              <span>Profile</span>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarFooter>
    </Sidebar>
  )
}
