
import { Toaster } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { SidebarProvider, SidebarInset, SidebarTrigger } from "@/components/ui/sidebar";
import { AppSidebar } from "@/components/AppSidebar";
import Index from "./pages/Index";
import NotFound from "./pages/NotFound";
import ERDPage from "./pages/ERD";
import History from "./pages/History";
import BookingIndoor from "./pages/BookingIndoor";
import BookingOutdoor from "./pages/BookingOutdoor";
import BookingSynthetic from "./pages/BookingSynthetic";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <SidebarProvider>
        <div className="min-h-screen flex w-full">
          <AppSidebar />
          <SidebarInset>
            <header className="flex h-16 shrink-0 items-center gap-2 border-b px-4">
              <SidebarTrigger className="-ml-1" />
              <div className="flex items-center space-x-2">
                <span className="text-lg font-semibold">ArenaKuy! Dashboard</span>
              </div>
            </header>
            <BrowserRouter>
              <Routes>
                <Route path="/" element={<Index />} />
                <Route path="/erd" element={<ERDPage />} />
                <Route path="/history" element={<History />} />
                <Route path="/booking/indoor" element={<BookingIndoor />} />
                <Route path="/booking/outdoor" element={<BookingOutdoor />} />
                <Route path="/booking/synthetic" element={<BookingSynthetic />} />
                <Route path="*" element={<NotFound />} />
              </Routes>
            </BrowserRouter>
          </SidebarInset>
        </div>
      </SidebarProvider>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
