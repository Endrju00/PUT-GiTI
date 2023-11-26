bot42Dest = Vector4d(300, 270, 0, 0)

bot42Reached = false


function bot42whatTo(agent, actorKnowledge, time)
    agent:moveTo(bot42Dest)
    if (reachedDestination(actorKnowledge, bot42Dest)) then
        if (not bot42Reached) then
            io.write("BOT42 REACHED DESTINATION", "\n")
            bot42Reached = true
        end
    end
end

function bot42onStart(agent, actorKnowledge, time)
    io.write("My name is: ", actorKnowledge:getName(), "\n")
    friends = actorKnowledge:getSeenFriends()
    if (friends:size() > 0) then
        bot42Dest = friends:at(0):getPosition() + Vector4d(-50, 0, 0, 0)
        agent:moveTo(bot42Dest)
    end
end
