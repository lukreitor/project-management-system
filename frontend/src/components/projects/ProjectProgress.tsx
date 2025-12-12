import { Progress } from '@/components/ui/progress';

interface ProjectProgressProps {
  progress: number;
  showLabel?: boolean;
  size?: 'sm' | 'md' | 'lg';
}

export default function ProjectProgress({ 
  progress, 
  showLabel = true,
  size = 'md' 
}: ProjectProgressProps) {
  const sizeClasses = {
    sm: 'h-2',
    md: 'h-3',
    lg: 'h-4'
  };

  const getProgressColor = (value: number) => {
    if (value === 100) return 'bg-green-500';
    if (value >= 70) return 'bg-blue-500';
    if (value >= 40) return 'bg-yellow-500';
    return 'bg-orange-500';
  };

  return (
    <div className="space-y-2">
      {showLabel && (
        <div className="flex justify-between items-center text-sm">
          <span className="text-muted-foreground">Progress</span>
          <span className="font-medium">{progress.toFixed(1)}%</span>
        </div>
      )}
      <div className="w-full bg-secondary rounded-full overflow-hidden">
        <div
          className={`${sizeClasses[size]} ${getProgressColor(progress)} transition-all duration-500 ease-out`}
          style={{ width: `${Math.min(progress, 100)}%` }}
        />
      </div>
    </div>
  );
}