import { Project } from '@/types';

interface ProjectCardProps {
  project: Project;
  onClick?: () => void;
}

export default function ProjectCard({ project, onClick }: ProjectCardProps) {
  return (
    <div
      className="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow cursor-pointer"
      onClick={onClick}
    >
      <div className="p-6">
        <div className="flex justify-between items-start mb-4">
          <h3 className="text-lg font-semibold">{project.name}</h3>
        </div>
        
        <div className="space-y-3">
          <div>
            <div className="flex justify-between items-center text-sm mb-1">
              <span className="text-muted-foreground">Progress</span>
              <span className="font-medium">{project.progress.toFixed(1)}%</span>
            </div>
            <div className="w-full bg-secondary rounded-full h-2">
              <div
                className="bg-primary h-2 rounded-full transition-all"
                style={{ width: `${project.progress}%` }}
              />
            </div>
          </div>
          
          <div className="text-xs text-muted-foreground">
            Created {new Date(project.created_at).toLocaleDateString()}
          </div>
        </div>
      </div>
    </div>
  );
}
