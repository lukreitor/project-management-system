import { Layers } from 'lucide-react';

export default function Header() {
  return (
    <header className="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="container mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
        <a className="flex items-center gap-3 transition-all hover:scale-105" href="/">
          <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
            <Layers className="h-6 w-6 text-primary-foreground" />
          </div>
          <span className="hidden font-bold text-lg sm:inline-block">Project Manager</span>
        </a>
        <nav className="flex items-center gap-6">
          <a
            href="/"
            className="text-sm font-medium transition-colors hover:text-primary flex items-center gap-2"
          >
            <span>Projects</span>
          </a>
        </nav>
      </div>
    </header>
  );
}
