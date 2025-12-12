import { Project } from '@/types';
import ProjectProgress from './ProjectProgress';

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
          <ProjectProgress progress={project.progress} size="sm" />

          <div className="text-xs text-muted-foreground">
            Created {new Date(project.created_at).toLocaleDateString()}
          </div>
        </div>
      </div>
    </div>
  );
}
