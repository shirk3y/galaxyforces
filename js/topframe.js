function blockFrames()
{
	if (top.frames.length>0)
	{
		top.location.href = self.location.href;
	}
}

addReadyEvent(blockFrames);
