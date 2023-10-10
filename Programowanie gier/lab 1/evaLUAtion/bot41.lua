bot41Dest = Vector4d(200, 270, 0, 0)

function reachedDestination(actorKnowledge)
    pos =  actorKnowledge:getPosition()
    return pos:value(0) == bot41Dest:value(0) and pos:value(1) == bot41Dest:value(1)
end

function bot41whatTo(agent, actorKnowledge, time)
    if (actorKnowledge:isMoving()) then
        io.write("I am walking", "\n")
        agent:moveTo(bot41Dest)
    elseif (reachedDestination(actorKnowledge)) then
        io.write("BOT41 REACHED DESTINATION", "\n")
    else
        io.write("I am not moving", "\n")
        pos = actorKnowledge:getPosition() + Vector4d(0, 10, 0, 0)
        agent:moveTo(pos)
    end
end

function bot41onStart(agent, actorKnowledge, time)
    io.write("My name is: ", actorKnowledge:getName(), "\n")
    friends = actorKnowledge:getSeenFriends()
    if (friends:size() > 0) then
        bot41Dest = friends:at(0):getPosition() + Vector4d(50, 0, 0, 0)
        agent:moveTo(bot41Dest)
    end
end
