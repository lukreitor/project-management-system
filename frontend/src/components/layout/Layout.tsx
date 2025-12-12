import { ReactNode } from 'react';
import Header from './Header';

interface LayoutProps {
  children: ReactNode;
}

export default function Layout({ children }: LayoutProps) {
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      <main className="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        {children}
      </main>
      <footer className="border-t py-6 md:py-0">
        <div className="container px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-between gap-4 md:h-16 md:flex-row">
          <p className="text-xs sm:text-sm text-muted-foreground">
            © 2025 Project Management System
          </p>
        </div>
      </footer>
    </div>
  );
}
